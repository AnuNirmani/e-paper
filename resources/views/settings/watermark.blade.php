<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Watermark Settings</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('settings.watermark.update') }}" method="POST" class="bg-white shadow-lg rounded-lg p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Vertical Position -->
            <div class="form-group">
                <label for="vertical_position" class="block text-sm font-semibold text-gray-700 mb-2">
                    Vertical Position
                </label>
                <select id="vertical_position" name="vertical_position" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach ($verticalOptions as $option)
                        <option value="{{ $option }}" {{ $settings['vertical_position'] === $option ? 'selected' : '' }}>
                            {{ ucfirst($option) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Horizontal Position -->
            <div class="form-group">
                <label for="horizontal_position" class="block text-sm font-semibold text-gray-700 mb-2">
                    Horizontal Position
                </label>
                <select id="horizontal_position" name="horizontal_position" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach ($horizontalOptions as $option)
                        <option value="{{ $option }}" {{ $settings['horizontal_position'] === $option ? 'selected' : '' }}>
                            {{ ucfirst($option) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Rotation -->
            <div class="form-group">
                <label for="rotation" class="block text-sm font-semibold text-gray-700 mb-2">
                    Rotation (degrees)
                </label>
                <div class="flex items-center space-x-4">
                    <input type="range" id="rotation" name="rotation" min="0" max="360" value="{{ $settings['rotation'] }}" class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                    <span class="text-lg font-semibold text-gray-900 w-16 text-right" id="rotation-value">{{ $settings['rotation'] }}°</span>
                </div>
            </div>

            <!-- Transparency -->
            <div class="form-group">
                <label for="transparency" class="block text-sm font-semibold text-gray-700 mb-2">
                    Transparency (0-100)
                </label>
                <div class="flex items-center space-x-4">
                    <input type="range" id="transparency" name="transparency" min="0" max="100" value="{{ $settings['transparency'] }}" class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                    <span class="text-lg font-semibold text-gray-900 w-16 text-right" id="transparency-value">{{ $settings['transparency'] }}%</span>
                </div>
            </div>

            <!-- Font Family -->
            <div class="form-group">
                <label for="font_family" class="block text-sm font-semibold text-gray-700 mb-2">
                    Font Family
                </label>
                <select id="font_family" name="font_family" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach ($fontFamilies as $font)
                        <option value="{{ $font }}" {{ $settings['font_family'] === $font ? 'selected' : '' }}>
                            {{ $font }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Font Size -->
            <div class="form-group">
                <label for="font_size" class="block text-sm font-semibold text-gray-700 mb-2">
                    Font Size (points)
                </label>
                <div class="flex items-center space-x-4">
                    <input type="range" id="font_size" name="font_size" min="8" max="72" value="{{ $settings['font_size'] }}" class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                    <span class="text-lg font-semibold text-gray-900 w-16 text-right" id="font_size-value">{{ $settings['font_size'] }}</span>
                </div>
            </div>

            <!-- Layer -->
            <div class="form-group">
                <label for="layer" class="block text-sm font-semibold text-gray-700 mb-2">
                    Layer Position
                </label>
                <select id="layer" name="layer" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach ($layerOptions as $option)
                        <option value="{{ $option }}" {{ $settings['layer'] === $option ? 'selected' : '' }}>
                            {{ ucfirst($option) }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Above: watermark appears on top of content | Below: watermark appears behind content</p>
            </div>

            <!-- Preview Section -->
            <div class="bg-gray-100 rounded-lg p-6 mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview</h3>
                <div class="bg-white border-2 border-dashed border-gray-300 rounded p-8 h-40 flex items-center justify-center relative overflow-hidden" id="preview">
                    <div style="transform: rotate(0deg); opacity: 0.3;" id="preview-text" class="text-center pointer-events-none">
                        <p class="font-sans text-4xl font-bold text-gray-400">Sample Watermark</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between pt-6">
                <!-- <a href="{{ route('dashboard') }}" class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    Back
                </a> -->
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Real-time preview updates
    const rotationInput = document.getElementById('rotation');
    const rotationValue = document.getElementById('rotation-value');
    const previewText = document.getElementById('preview-text');
    const fontSizeInput = document.getElementById('font_size');
    const fontSizeValue = document.getElementById('font_size-value');
    const transparencyInput = document.getElementById('transparency');
    const transparencyValue = document.getElementById('transparency-value');
    const fontFamilyInput = document.getElementById('font_family');

    function updatePreview() {
        const rotation = rotationInput.value;
        const fontSize = fontSizeInput.value;
        const transparency = transparencyInput.value;
        const fontFamily = fontFamilyInput.value;

        previewText.style.transform = `rotate(${rotation}deg)`;
        previewText.style.opacity = (100 - transparency) / 100;
        previewText.style.fontSize = (fontSize / 40) * 32 + 'px';
        previewText.style.fontFamily = fontFamily;

        rotationValue.textContent = rotation + '°';
        fontSizeValue.textContent = fontSize;
        transparencyValue.textContent = transparency + '%';
    }

    rotationInput.addEventListener('input', updatePreview);
    fontSizeInput.addEventListener('input', updatePreview);
    transparencyInput.addEventListener('input', updatePreview);
    fontFamilyInput.addEventListener('change', updatePreview);

    // Initialize preview
    updatePreview();
</script>
</x-app-layout>
