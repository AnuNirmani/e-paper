<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Watermark Settings</h1>
            <button type="button" id="edit-btn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                Edit
            </button>
        </div>

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

        <form id="watermark-form" action="{{ route('settings.watermark.update') }}" method="POST" class="bg-white shadow-lg rounded-lg p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Vertical Position -->
            <div class="form-group">
                <label for="vertical_position" class="block text-sm font-semibold text-gray-700 mb-2">
                    Vertical Position
                </label>
                <select id="vertical_position" name="vertical_position" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled>
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
                <select id="horizontal_position" name="horizontal_position" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled>
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
                    <input type="range" id="rotation" name="rotation" min="0" max="360" value="{{ $settings['rotation'] }}" class="slider-input flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" disabled>
                    <span class="text-lg font-semibold text-gray-900 w-16 text-right" id="rotation-value">{{ $settings['rotation'] }}°</span>
                </div>
            </div>

            <!-- Transparency -->
            <div class="form-group">
                <label for="transparency" class="block text-sm font-semibold text-gray-700 mb-2">
                    Transparency (0-100)
                </label>
                <div class="flex items-center space-x-4">
                    <input type="range" id="transparency" name="transparency" min="0" max="100" value="{{ $settings['transparency'] }}" class="slider-input flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" disabled>
                    <span class="text-lg font-semibold text-gray-900 w-16 text-right" id="transparency-value">{{ $settings['transparency'] }}%</span>
                </div>
            </div>

            <!-- Font Family -->
            <div class="form-group">
                <label for="font_family" class="block text-sm font-semibold text-gray-700 mb-2">
                    Font Family
                </label>
                <select id="font_family" name="font_family" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled>
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
                    <input type="range" id="font_size" name="font_size" min="8" max="72" value="{{ $settings['font_size'] }}" class="slider-input flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" disabled>
                    <span class="text-lg font-semibold text-gray-900 w-16 text-right" id="font_size-value">{{ $settings['font_size'] }}</span>
                </div>
            </div>

            <!-- Layer -->
            <div class="form-group">
                <label for="layer" class="block text-sm font-semibold text-gray-700 mb-2">
                    Layer Position
                </label>
                <select id="layer" name="layer" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled>
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
                <div class="bg-white border-2 border-dashed border-gray-300 rounded p-8 h-64 flex items-center justify-center relative overflow-hidden" id="preview">
                        <div id="preview-text" class="text-center pointer-events-none" style="transform: rotate(0deg); opacity: 0.3; font-family: Arial;">
                        <p class="font-bold text-gray-400 m-0">Sample Watermark</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-between pt-6" id="action-buttons" style="display: none;">
                <button type="button" id="cancel-btn" class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Default slider thumb styling */
    .slider-input::-webkit-slider-thumb {
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #9CA3AF;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .slider-input::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #9CA3AF;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s;
    }

    /* Blue slider thumb when enabled (edit mode) */
    .slider-input:not([disabled])::-webkit-slider-thumb {
        background: #2563EB;
    }

    .slider-input:not([disabled])::-moz-range-thumb {
        background: #2563EB;
    }

    /* Hover effect for enabled sliders */
    .slider-input:not([disabled]):hover::-webkit-slider-thumb {
        background: #1D4ED8;
    }

    .slider-input:not([disabled]):hover::-moz-range-thumb {
        background: #1D4ED8;
    }
</style>

<script>
    // Edit mode functionality
    const editBtn = document.getElementById('edit-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const actionButtons = document.getElementById('action-buttons');
    const form = document.getElementById('watermark-form');
    const formInputs = form.querySelectorAll('input, select');
    
    let isEditMode = false;
    let originalValues = {};

    // Save original values
    function saveOriginalValues() {
        originalValues = {};
        formInputs.forEach(input => {
            originalValues[input.id || input.name] = input.value;
        });
    }

    // Restore original values
    function restoreOriginalValues() {
        formInputs.forEach(input => {
            const key = input.id || input.name;
            if (originalValues[key] !== undefined) {
                input.value = originalValues[key];
            }
        });
        updatePreview();
    }

    // Toggle edit mode
    function toggleEditMode() {
        isEditMode = !isEditMode;
        
        if (isEditMode) {
            // Enable editing
            saveOriginalValues();
            formInputs.forEach(input => input.disabled = false);
            editBtn.textContent = 'Editing...';
            editBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            editBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            editBtn.disabled = true;
            actionButtons.style.display = 'flex';
        } else {
            // Disable editing
            formInputs.forEach(input => input.disabled = true);
            editBtn.textContent = 'Edit';
            editBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            editBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            editBtn.disabled = false;
            actionButtons.style.display = 'none';
        }
    }

    // Event listeners
    editBtn.addEventListener('click', toggleEditMode);
    
    cancelBtn.addEventListener('click', () => {
        restoreOriginalValues();
        toggleEditMode();
    });

    // Initialize with disabled state
    saveOriginalValues();

    // Real-time preview updates
    const rotationInput = document.getElementById('rotation');
    const rotationValue = document.getElementById('rotation-value');
    const previewText = document.getElementById('preview-text');
    const preview = document.getElementById('preview');
    const fontSizeInput = document.getElementById('font_size');
    const fontSizeValue = document.getElementById('font_size-value');
    const transparencyInput = document.getElementById('transparency');
    const transparencyValue = document.getElementById('transparency-value');
    const fontFamilyInput = document.getElementById('font_family');
    const verticalPositionInput = document.getElementById('vertical_position');
    const horizontalPositionInput = document.getElementById('horizontal_position');

    // Map position values to CSS alignment
    function getVerticalAlignment(position) {
        switch(position) {
            case 'top': return 'flex-start';
            case 'middle': return 'center';
            case 'bottom': return 'flex-end';
            default: return 'center';
        }
    }

    function getHorizontalAlignment(position) {
        switch(position) {
            case 'left': return 'flex-start';
            case 'center': return 'center';
            case 'right': return 'flex-end';
            default: return 'center';
        }
    }

    function updatePreview() {
        const rotation = rotationInput.value;
        const fontSize = fontSizeInput.value;
        const transparency = transparencyInput.value;
        const fontFamily = fontFamilyInput.value;
        const verticalPosition = verticalPositionInput.value;
        const horizontalPosition = horizontalPositionInput.value;

        // Update text styles
        previewText.style.transform = `rotate(${rotation}deg)`;
        previewText.style.opacity = (100 - transparency) / 100;
        previewText.style.fontSize = (fontSize / 40) * 32 + 'px';
        previewText.style.fontFamily = fontFamily + ', sans-serif';

        // Update preview container alignment
        preview.style.justifyContent = getHorizontalAlignment(horizontalPosition);
        preview.style.alignItems = getVerticalAlignment(verticalPosition);

        // Update display values
        rotationValue.textContent = rotation + '°';
        fontSizeValue.textContent = fontSize;
        transparencyValue.textContent = transparency + '%';
    }

    rotationInput.addEventListener('input', updatePreview);
    fontSizeInput.addEventListener('input', updatePreview);
    transparencyInput.addEventListener('input', updatePreview);
    fontFamilyInput.addEventListener('change', updatePreview);
    verticalPositionInput.addEventListener('change', updatePreview);
    horizontalPositionInput.addEventListener('change', updatePreview);

    // Initialize preview
    updatePreview();
</script>
</x-app-layout>