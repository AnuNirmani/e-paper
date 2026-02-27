<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index()
    {
        $sort = request('sort');
        $publicationId = request('publication_id');

        $customerQuery = Customer::search(request('search'))
            ->where('status', '!=', -1); // keep soft-deleted customers hidden

        // Optional status filter (e.g., status=active)
        if (request('status') === 'active') {
            $customerQuery->where('status', 1);
        }

        // Filter by publication if publication_id is provided
        if ($publicationId) {
            $customerQuery->whereHas('publications', function($q) use ($publicationId) {
                $q->where('publication_id', $publicationId);
            });
        }

        if ($sort === 'ending_today') {
            $today = Carbon::today();
            // Push rows with today's ending_date to the top, then sort by nearest ending_date
            $customerQuery->orderByRaw('DATE(ending_date) = ? DESC', [$today->toDateString()])
                ->orderBy('ending_date', 'asc')
                ->orderBy('id', 'desc');
        } elseif ($sort === 'id') {
            // Explicit id sort (latest first)
            $customerQuery->orderBy('id', 'desc');
        } else {
            $customerQuery->orderBy('id', 'desc');
        }

        $customers = $customerQuery->paginate(10)->withQueryString();
        $activeCount = Customer::getActiveCount();

        // Get all publications and their active account counts
        $publicationStats = [];
        $publications = \App\Models\Publication::where('status', '!=', -1)
            ->orderBy('name')
            ->get();
        foreach ($publications as $publication) {
            $activeAccounts = $publication->customers()->where('status', 1)->count();
            $publicationStats[] = [
                'id' => $publication->id,
                'name' => $publication->name,
                'active_accounts' => $activeAccounts
            ];
        }

        $selectedPublication = null;
        if ($publicationId) {
            $selectedPublication = \App\Models\Publication::find($publicationId);
        }

        return view('customers.index', compact('customers', 'activeCount', 'publicationStats', 'selectedPublication'));
    }

    public function create()
    {
        $publications = \App\Models\Publication::where('status', 1)->orderBy('name')->get();
        return view('customers.create', compact('publications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:255',
            'email'           => 'email:rfc,dns|unique:customers,email',
            'order_id'        => 'required|string|max:255|unique:customers,order_id',
            'starting_date'   => 'required|date',
            'ending_date'     => 'required|date|after_or_equal:starting_date',
            'country'         => 'required|string|max:255',
            'status'          => 'required|in:1,0',
            'address'         => 'nullable|string|max:255',
            'city'            => 'nullable|string|max:255',
            'province'        => 'nullable|string|max:255',
            'zip_code'        => 'nullable|string|max:255',
            'duration'        => 'required|integer|min:0',
            'payment_method'  => 'required|in:online,bank_transfer',
            'payment_amount'  => 'required|numeric|min:0',
            'payment_receipt' => 'required|boolean',
        ]);

        $validated['order_id'] = trim($validated['order_id']);

        // Extract publication IDs
        $publicationIds = [];
        if ($request->has('publications')) {
            foreach ($request->input('publications') as $pubId => $pub) {
                if (isset($pub['selected'])) {
                    $publicationIds[] = (int)$pubId;
                }
            }
        }

        // Validate at least one publication is selected
        if (empty($publicationIds)) {
            return redirect()->back()
                ->withErrors(['publications' => 'Please select at least one publication.'])
                ->withInput();
        }

        // Calculate pricing
        $pricing = $this->buildPricing($publicationIds, (int)$validated['duration']);
        $validated['payment_amount'] = $pricing['total'];

        $customer = Customer::storeCustomer($validated);
        $customer->publications()->sync($pricing['pivot']);

        return redirect()->route('customers.index')
            ->with('success', 'Customer added (Total: Rs ' . number_format($pricing['total'], 2) . ')');
    }


    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $publications = \App\Models\Publication::where('status', 1)->orderBy('name')->get();
        $customerPublications = $customer->publications()->pluck('publication_id')->toArray();

        return view('customers.edit', compact('customer', 'publications', 'customerPublications'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:255',
            'email'           => 'required|email:rfc,dns|unique:customers,email,' . $id . ',id',
            'order_id'        => 'required|string|max:255|unique:customers,order_id,' . $id . ',id',
            'starting_date'   => 'required|date',
            'ending_date'     => 'required|date|after_or_equal:starting_date',
            'country'         => 'required|string|max:255',
            'status'          => 'required|in:1,0',
            'address'         => 'nullable|string|max:255',
            'city'            => 'nullable|string|max:255',
            'province'        => 'nullable|string|max:255',
            'zip_code'        => 'nullable|string|max:255',
            'duration'        => 'required|integer|min:0',
            'payment_method'  => 'required|in:online,bank_transfer',
            'payment_amount'  => 'required|numeric|min:0',
            'payment_receipt' => 'required|boolean',
        ]);

        $validated['order_id'] = trim($validated['order_id']);

        // Extract publication IDs
        $publicationIds = [];
        if ($request->has('publications')) {
            foreach ($request->input('publications') as $pubId => $pub) {
                if (isset($pub['selected'])) {
                    $publicationIds[] = (int)$pubId;
                }
            }
        }

        // Validate at least one publication is selected
        if (empty($publicationIds)) {
            return redirect()->back()
                ->withErrors(['publications' => 'Please select at least one publication.'])
                ->withInput();
        }

        // Calculate pricing
        $pricing = $this->buildPricing($publicationIds, (int)$validated['duration']);
        $validated['payment_amount'] = $pricing['total'];

        Customer::updateCustomer($id, $validated);
        $customer = Customer::find($id);
        $customer->publications()->sync($pricing['pivot']);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated (Total: Rs ' . number_format($pricing['total'], 2) . ')');
    }

    /**
     * Calculate total and per-publication pricing for a date range.
     */
    private function buildPricing(array $publicationIds, int $duration): array
    {
        $pivot = [];
        $total = 0.0;

        $publications = \App\Models\Publication::whereIn('id', $publicationIds)->get(['id', 'price', 'days_per_month']);
        foreach ($publications as $publication) {
            $daily = (float) ($publication->price ?? 0);
            $daysPerMonth = (int) ($publication->days_per_month ?? 30);
            
            // New logic: duration * days_per_month * daily_price
            $lineTotal = round($duration * $daysPerMonth * $daily, 2);

            $pivot[$publication->id] = ['price' => $lineTotal];
            $total += $lineTotal;
        }

        return ['pivot' => $pivot, 'total' => round($total, 2)];
    }

    public function destroy($id)
    {
        Customer::deleteCustomer($id);
        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted');
    }
    public function activateAll()
    {
        Customer::activateAll();
        return redirect()->route('customers.index')->with('success', 'All customers activated.');
    }

    public function deactivateAll()
    {
        Customer::deactivateAll();
        return redirect()->route('customers.index')->with('success', 'All customers deactivated.');
    }

    public function toggleStatus($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(['status' => $customer->status == 1 ? 0 : 1]);
        
        return response()->json([
            'success' => true,
            'status' => $customer->status,
            'message' => $customer->status == 1 ? 'Customer activated' : 'Customer deactivated'
        ]);
    }
}
