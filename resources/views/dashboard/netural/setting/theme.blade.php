{{-- @php
    $hasSettingAccess =
        auth()->user()->can('setting view') ||
        auth()->user()->can('setting->applogo view') ||
        auth()->user()->can('setting->favicon view') ||
        auth()->user()->can('setting->sitecontrol view') ||
        auth()->user()->can('setting->themecolor view');

@endphp --}}
<x-tabnav>
    @section('title', 'Theme Color')
    <div class="main-container">
        <!-- Vertical Tabs Navigation -->
        <div class="vertical-tabs">
            {{-- @if ($hasSettingAccess) --}}
            <h6 class="fw-bold text-center mb-3 head">Setting</h6>
            {{-- @can('setting->applogo view') --}}
            @if (auth()->user()->hasRole('Super admin'))
                <a href="{{ route('applogo') }}" onclick="switchTab('tab1', event)">
                    <div class="tab-header {{ request()->routeIs('applogo') ? 'active' : '' }}">
                        <h1>Web App Logo</h1>
                    </div>
                </a>
                {{-- @endcan
                @can('setting->favicon view') --}}
                <a href="{{ route('favicon') }}" onclick="switchTab('tab2', event)">
                    <div class="tab-header {{ request()->routeIs('favicon') ? 'active' : '' }}">
                        <h2>Favicon</h2>
                    </div>
                </a>
                {{-- @endcan
                @can('setting->sitecontrol view') --}}
                <a href="{{ route('sitecontrol') }}" onclick="switchTab('tab3', event)">
                    <div class="tab-header {{ request()->routeIs('sitecontrol') ? 'active' : '' }}">
                        <h3>Maintenance</h3>
                    </div>
                </a>
            @endif
            {{-- @endcan
                @can('setting->themecolor view') --}}
            <a href="{{ route('theme') }}" onclick="switchTab('tab4', event)">
                <div class="tab-header {{ request()->routeIs('theme') ? 'active' : '' }}">
                    <h3>Theme Color</h3>
                </div>
            </a>
            {{-- @endcan --}} <a href="{{ route('cookies') }}" onclick="switchTab('tab5', event)">
                <div class="tab-header {{ request()->routeIs('cookies') ? 'active' : '' }}">
                    <h3>Cookies & Sessions</h3>
                </div>
            </a>
            {{-- @endif --}}
        </div>

        <x-message />

        <!-- Tab Content Areas -->
        <div class="tab-content">
            <div id="tab1" class="tab-pane {{ request()->routeIs('theme') ? 'active' : '' }}">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-2">Website Theme Customization</h3>
                        <p class="text-muted mb-0">Craft your unique visual identity with advanced color controls</p>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary mt-2 mt-md-0">
                        <i class="bi bi-palette me-1"></i> Theme Setting
                    </span>
                </div>
            </div>

            <div class="card border-0 rounded-4 overflow-hidden glass-morphism-effect">
                <div class="card-body p-5">
                    <form class="needs-validation" action="{{ route('theme_post') }}" novalidate method="post">
                        @csrf

                        <!-- Hidden field to store selected palette -->
                        <input type="hidden" name="enable_palettes" id="enablePalettesField"
                            value="{{ $settings->enable_palettes ?? 0 }}">
                        <input type="hidden" name="enable_custom_colors" id="enableCustomColorsField"
                            value="{{ $settings->enable_custom_colors ?? 1 }}">
                        <input type="hidden" name="selected_palette" id="selectedPalette"
                            value="{{ $settings->selected_palette ?? '' }}">

                        <!-- Color Palettes -->
                        <div class="mb-5">
                            <div class="fw-semibold mb-3 fs-5">
                                <input class="form-check-input palette-checkbox" type="checkbox" id="paletteCheckbox"
                                    name="enable_palettes" value="1"
                                    {{ old('enable_palettes', $settings->enable_palettes ?? false) ? 'checked' : '' }} />
                                <label for="paletteCheckbox" class="ms-2">Color Palettes</label>
                            </div>




                            <div class="row g-3 palette-carousel" id="paletteSection"
                                style="{{ old('enable_palettes', $settings->enable_palettes ?? false) ? '' : 'opacity: 0.5; pointer-events: none;' }}">
                                <!-- Ocean Breeze Palette -->
                                <div class="col-lg-4">
                                    <div class="palette-card" data-palette="ocean">
                                        <div class="palette-colors">
                                            <span style="background-color: #3a86ff"></span>
                                            <span style="background-color: #a0c4fd"></span>
                                            <span style="background-color: #e0ecff"></span>
                                            <span style="background-color: #e0ecff"></span>
                                        </div>
                                        <div class="palette-info">
                                            <h6>Ocean Breeze</h6>
                                            <small>Cool blue tones</small>
                                        </div>
                                        <div class="text-end">
                                            <button type="button"
                                                class="btn btn-sm apply-palette-btn {{ ($settings->selected_palette ?? '') == 'ocean' ? 'btn-success' : 'btn-outline-primary' }}"
                                                data-palette="ocean">
                                                @if (($settings->selected_palette ?? '') == 'ocean')
                                                    <i class="bi bi-check-circle-fill me-1"></i> Applied
                                                @else
                                                    <span class="btn-content">Apply</span>
                                                    <span class="btn-loader" style="display: none;">
                                                        <span class="spinner-border spinner-border-sm"
                                                            role="status"></span>
                                                    </span>
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Emerald Forest Palette -->
                                <div class="col-lg-4">
                                    <div class="palette-card" data-palette="forest">
                                        <div class="palette-colors">
                                            <span style="background-color: #2a9d8f"></span>
                                            <span style="background-color: #afe9e2"></span>
                                            <span style="background-color: #e0fffb"></span>
                                            <span style="background-color: #e0fffb"></span>
                                        </div>
                                        <div class="palette-info">
                                            <h6>Emerald Forest</h6>
                                            <small>Natural green tones</small>
                                        </div>
                                        <div class="text-end">
                                            <button type="button"
                                                class="btn btn-sm apply-palette-btn {{ ($settings->selected_palette ?? '') == 'forest' ? 'btn-success' : 'btn-outline-primary' }}"
                                                data-palette="forest">
                                                @if (($settings->selected_palette ?? '') == 'forest')
                                                    <i class="bi bi-check-circle-fill me-1"></i> Applied
                                                @else
                                                    <span class="btn-content">Apply</span>
                                                    <span class="btn-loader" style="display: none;">
                                                        <span class="spinner-border spinner-border-sm"
                                                            role="status"></span>
                                                    </span>
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Sunset Glow Palette -->
                                <div class="col-lg-4">
                                    <div class="palette-card" data-palette="sunset">
                                        <div class="palette-colors">
                                            <span style="background-color: #e76f51"></span>
                                            <span style="background-color: #d9b4ab"></span>
                                            <span style="background-color: #f7e7e4"></span>
                                            <span style="background-color: #f7e7e4"></span>
                                        </div>
                                        <div class="palette-info">
                                            <h6>Sunset Glow</h6>
                                            <small>Warm orange tones</small>
                                        </div>
                                        <div class="text-end">
                                            <button type="button"
                                                class="btn btn-sm apply-palette-btn {{ ($settings->selected_palette ?? '') == 'sunset' ? 'btn-success' : 'btn-outline-primary' }}"
                                                data-palette="sunset">
                                                @if (($settings->selected_palette ?? '') == 'sunset')
                                                    <i class="bi bi-check-circle-fill me-1"></i> Applied
                                                @else
                                                    <span class="btn-content">Apply</span>
                                                    <span class="btn-loader" style="display: none;">
                                                        <span class="spinner-border spinner-border-sm"
                                                            role="status"></span>
                                                    </span>
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>

                        <div class="row g-4">
                            <div class="fw-semibold mb-3 fs-5">
                                <input class="form-check-input custom-color-checkbox" type="checkbox"
                                    id="customColorCheckbox" name="enable_custom_colors" value="1"
                                    {{ old('enable_custom_colors', $settings->enable_custom_colors ?? true) ? 'checked' : '' }} />
                                <label for="customColorCheckbox" class="ms-2">Custom Color</label>
                            </div>
                            <!-- Primary Color -->
                            <div class="col-md-6" id="customColorSection"
                                style="{{ old('enable_custom_colors', $settings->enable_custom_colors ?? true) ? '' : 'opacity: 0.5; pointer-events: none;' }}">
                                <div class="color-picker-card p-4 rounded-3 border-0 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-droplet-fill me-2 text-primary"></i> Primary Color
                                        </label>
                                        <div class="hex-code-container" data-color="p_color">
                                            <span class="hex-code">{{ $settings->p_color ?? '#4477b6' }}</span>
                                            <button type="button" class="btn-copy-hex ms-2"
                                                title="Copy to clipboard">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                            <span class="copied-message text-success small ms-2"
                                                style="display: none;">Copied!</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="color-picker-wrapper">
                                            <input type="color" name="p_color" id="p_color"
                                                class="form-control form-control-color modern-color-picker"
                                                value="{{ $settings->p_color ?? '#4477b6' }}"
                                                title="Choose primary color">
                                            <div class="color-picker-preview"></div>
                                        </div>
                                        <span class="ms-3 text-muted small">Main brand color used throughout</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Nav Header Color -->
                            <div class="col-md-6" id="customColorSection2"
                                style="{{ old('enable_custom_colors', $settings->enable_custom_colors ?? true) ? '' : 'opacity: 0.5; pointer-events: none;' }}">
                                <div class="color-picker-card p-4 rounded-3 border-0 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-menu-button-wide me-2 text-info"></i> Navigation Header
                                        </label>
                                        <div class="hex-code-container" data-color="nh_color">
                                            <span class="hex-code">{{ $settings->nh_color ?? '#ffffff' }}</span>
                                            <button type="button" class="btn-copy-hex ms-2"
                                                title="Copy to clipboard">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                            <span class="copied-message text-success small ms-2"
                                                style="display: none;">Copied!</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="color-picker-wrapper">
                                            <input type="color" name="nh_color" id="nh_color"
                                                class="form-control form-control-color modern-color-picker"
                                                value="{{ $settings->nh_color ?? '#ffffff' }}"
                                                title="Choose nav header color">
                                            <div class="color-picker-preview"></div>
                                        </div>
                                        <span class="ms-3 text-muted small">Top navigation bar color</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Header Color -->
                            <div class="col-md-6" id="customColorSection3"
                                style="{{ old('enable_custom_colors', $settings->enable_custom_colors ?? true) ? '' : 'opacity: 0.5; pointer-events: none;' }}">
                                <div class="color-picker-card p-4 rounded-3 border-0 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-layout-sidebar-inset me-2 text-warning"></i> Header
                                        </label>
                                        <div class="hex-code-container" data-color="h_color">
                                            <span class="hex-code">{{ $settings->h_color ?? '#ffffff' }}</span>
                                            <button type="button" class="btn-copy-hex ms-2"
                                                title="Copy to clipboard">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                            <span class="copied-message text-success small ms-2"
                                                style="display: none;">Copied!</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="color-picker-wrapper">
                                            <input type="color" name="h_color" id="h_color"
                                                class="form-control form-control-color modern-color-picker"
                                                value="{{ $settings->h_color ?? '#ffffff' }}"
                                                title="Choose header color">
                                            <div class="color-picker-preview"></div>
                                        </div>
                                        <span class="ms-3 text-muted small">Content header section</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar Color -->
                            <div class="col-md-6" id="customColorSection4"
                                style="{{ old('enable_custom_colors', $settings->enable_custom_colors ?? true) ? '' : 'opacity: 0.5; pointer-events: none;' }}">
                                <div class="color-picker-card p-4 rounded-3 border-0 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-columns-gap me-2 text-success"></i> Sidebar
                                        </label>
                                        <div class="hex-code-container" data-color="s_color">
                                            <span class="hex-code">{{ $settings->s_color ?? '#ffffff' }}</span>
                                            <button type="button" class="btn-copy-hex ms-2"
                                                title="Copy to clipboard">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                            <span class="copied-message text-success small ms-2"
                                                style="display: none;">Copied!</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="color-picker-wrapper">
                                            <input type="color" name="s_color" id="s_color"
                                                class="form-control form-control-color modern-color-picker"
                                                value="{{ $settings->s_color ?? '#ffffff' }}"
                                                title="Choose sidebar color">
                                            <div class="color-picker-preview"></div>
                                        </div>
                                        <span class="ms-3 text-muted small">Main navigation sidebar</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end align-items-center pt-5 mt-3" id="customColorSection5">
                            <button type="button" class="btn btn-secondary me-2 reset-btn">
                                <span class="btn-content">Reset</span>
                                <span class="btn-loader" style="display: none;">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </button>
                            <div>
                                <button type="submit" id="submit_form" class="btn btn-primary submit-btn">
                                    <span class="btn-content">Update</span>
                                    <span class="btn-loader" style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize CSS variables with current values
                document.querySelectorAll('.modern-color-picker').forEach(picker => {
                    const name = picker.getAttribute('name');
                    const value = picker.value;
                    updateCssVariable(name, value);

                    // Initialize preview color
                    const preview = picker.nextElementSibling;
                    preview.style.backgroundColor = value;
                });

                // Function to update CSS variable based on input name
                function updateCssVariable(inputName, value) {
                    let cssVarName;

                    switch (inputName) {
                        case 'p_color':
                            cssVarName = '--p-color';
                            break;
                        case 'nh_color':
                            cssVarName = '--nh-color';
                            break;
                        case 'h_color':
                            cssVarName = '--h-color';
                            break;
                        case 's_color':
                            cssVarName = '--s-color';
                            break;
                    }

                    if (cssVarName) {
                        document.documentElement.style.setProperty(cssVarName, value);
                    }
                }

                // Handle live color changes
                document.querySelectorAll('.modern-color-picker').forEach(picker => {
                    picker.addEventListener('input', function() {
                        // When any custom color is changed, switch to custom color mode
                        switchToCustomColorMode();

                        const color = this.value;
                        const name = this.getAttribute('name');
                        const preview = this.nextElementSibling;
                        const hexCodeContainer = this.closest('.color-picker-card').querySelector(
                            '.hex-code');

                        preview.style.backgroundColor = color;
                        if (hexCodeContainer) {
                            hexCodeContainer.textContent = color;
                        }
                        updateCssVariable(name, color);
                    });
                });

                // Checkbox handling
                const paletteCheckbox = document.querySelector('.palette-checkbox');
                const customColorCheckbox = document.querySelector('.custom-color-checkbox');
                const paletteSection = document.getElementById('paletteSection');
                const customColorSections = [
                    document.getElementById('customColorSection'),
                    document.getElementById('customColorSection2'),
                    document.getElementById('customColorSection3'),
                    document.getElementById('customColorSection4'),
                    document.getElementById('customColorSection5'),
                ];

                function switchToCustomColorMode() {
                    document.getElementById('enableCustomColorsField').value = '1';
                    document.getElementById('enablePalettesField').value = '0';
                    document.getElementById('selectedPalette').value = '';
                    document.querySelector('.custom-color-checkbox').checked = true;
                    document.querySelector('.palette-checkbox').checked = false;
                    handleCheckboxChange(document.querySelector('.custom-color-checkbox'));
                }

                function switchToPaletteMode(paletteName = null) {
                    document.getElementById('enablePalettesField').value = '1';
                    document.getElementById('enableCustomColorsField').value = '0';
                    if (paletteName) {
                        document.getElementById('selectedPalette').value = paletteName;
                    }
                    document.querySelector('.palette-checkbox').checked = true;
                    document.querySelector('.custom-color-checkbox').checked = false;
                    handleCheckboxChange(document.querySelector('.palette-checkbox'));
                }

                function handleCheckboxChange(checkedCheckbox) {
                    if (checkedCheckbox === paletteCheckbox) {
                        // Switching to palette mode
                        customColorCheckbox.checked = false;
                        document.getElementById('enablePalettesField').value = '1';
                        document.getElementById('enableCustomColorsField').value = '0';

                        // Disable custom color sections
                        customColorSections.forEach(section => {
                            if (section) {
                                section.style.opacity = '0.5';
                                section.style.pointerEvents = 'none';
                            }
                        });
                        // Enable palette section
                        paletteSection.style.opacity = '1';
                        paletteSection.style.pointerEvents = 'auto';
                    } else if (checkedCheckbox === customColorCheckbox) {
                        // Switching to custom color mode
                        paletteCheckbox.checked = false;
                        document.getElementById('enablePalettesField').value = '0';
                        document.getElementById('enableCustomColorsField').value = '1';
                        document.getElementById('selectedPalette').value = '';

                        // Disable palette section
                        paletteSection.style.opacity = '0.5';
                        paletteSection.style.pointerEvents = 'none';
                        // Enable custom color sections
                        customColorSections.forEach(section => {
                            if (section) {
                                section.style.opacity = '1';
                                section.style.pointerEvents = 'auto';
                            }
                        });

                        // Reset all palette buttons to default state
                        resetPaletteButtons();
                    }
                }

                function resetPaletteButtons() {
                    document.querySelectorAll('.apply-palette-btn').forEach(btn => {
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-outline-primary');
                        btn.innerHTML = `
                <span class="btn-content">Apply</span>
                <span class="btn-loader" style="display: none;">
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                </span>
            `;
                    });
                }

                // Initialize sections based on checkbox state
                if (paletteCheckbox.checked) {
                    handleCheckboxChange(paletteCheckbox);
                } else if (customColorCheckbox.checked) {
                    handleCheckboxChange(customColorCheckbox);
                }

                // Add event listeners
                paletteCheckbox.addEventListener('change', function() {
                    handleCheckboxChange(this);
                });

                customColorCheckbox.addEventListener('change', function() {
                    handleCheckboxChange(this);
                });

                // Color palette functionality
                const palettes = {
                    ocean: {
                        p_color: '#3a86ff',
                        nh_color: '#a0c4fd',
                        h_color: '#e0ecff',
                        s_color: '#e0ecff'
                    },
                    forest: {
                        p_color: '#2a9d8f',
                        nh_color: '#afe9e2',
                        h_color: '#e0fffb',
                        s_color: '#e0fffb'
                    },
                    sunset: {
                        p_color: '#e76f51',
                        nh_color: '#d9b4ab',
                        h_color: '#f7e7e4',
                        s_color: '#f7e7e4'
                    }
                };

                // Handle palette apply button clicks
                // Modify the palette apply button click handler
                document.querySelectorAll('.apply-palette-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault(); // Prevent immediate form submission

                        const paletteName = this.dataset.palette;

                        // 1. Update all hidden fields FIRST
                        document.getElementById('selectedPalette').value = paletteName;
                        document.getElementById('enablePalettesField').value = '1';
                        document.getElementById('enableCustomColorsField').value = '0';

                        // 2. Update checkboxes and UI state
                        document.querySelector('.palette-checkbox').checked = true;
                        document.querySelector('.custom-color-checkbox').checked = false;
                        handleCheckboxChange(document.querySelector('.palette-checkbox'));

                        // 3. Update color inputs (but DON'T trigger input events)
                        const palettes = {
                            ocean: {
                                p_color: '#3a86ff',
                                nh_color: '#a0c4fd',
                                h_color: '#e0ecff',
                                s_color: '#e0ecff'
                            },
                            forest: {
                                p_color: '#2a9d8f',
                                nh_color: '#afe9e2',
                                h_color: '#e0fffb',
                                s_color: '#e0fffb'
                            },
                            sunset: {
                                p_color: '#e76f51',
                                nh_color: '#d9b4ab',
                                h_color: '#f7e7e4',
                                s_color: '#f7e7e4'
                            }
                        };

                        Object.entries(palettes[paletteName]).forEach(([name, value]) => {
                            const input = document.querySelector(`input[name="${name}"]`);
                            if (input) {
                                input.value = value;
                                // Update preview without triggering input event
                                const preview = input.nextElementSibling;
                                preview.style.backgroundColor = value;
                                // Update hex display
                                const hexCode = input.closest('.color-picker-card')
                                    .querySelector('.hex-code');
                                if (hexCode) hexCode.textContent = value;
                            }
                        });

                        // 4. Update button states
                        document.querySelectorAll('.apply-palette-btn').forEach(btn => {
                            if (btn.dataset.palette === paletteName) {
                                btn.innerHTML =
                                    '<i class="bi bi-check-circle-fill me-1"></i> Applied';
                                btn.classList.add('btn-success');
                                btn.classList.remove('btn-outline-primary');
                            } else {
                                btn.innerHTML = '<span class="btn-content">Apply</span>';
                                btn.classList.remove('btn-success');
                                btn.classList.add('btn-outline-primary');
                            }
                        });

                        // 5. Submit the form programmatically after a small delay
                        setTimeout(() => {
                            document.getElementById('submit_form').click();
                        }, 100);
                    });
                });
                // Form submission animation
                const form = document.querySelector('form');
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('.submit-btn');
                    const btnContent = submitBtn.querySelector('.btn-content');
                    const btnLoader = submitBtn.querySelector('.btn-loader');

                    btnContent.style.display = 'none';
                    btnLoader.style.display = 'inline-block';

                    // Add pulse animation to the card
                    const card = this.closest('.card');
                    card.style.animation = 'none';
                    void card.offsetWidth;
                    card.style.animation = 'pulse 0.5s ease';
                });

                // Reset button functionality
                document.querySelector('.reset-btn').addEventListener('click', function(e) {
                    e.preventDefault();

                    const btnContent = this.querySelector('.btn-content');
                    const btnLoader = this.querySelector('.btn-loader');

                    btnContent.style.display = 'none';
                    btnLoader.style.display = 'inline-block';

                    fetch('{{ route('theme.reset') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) {
                            window.location.reload();
                        }
                    });
                });

                // Copy hex code to clipboard
                document.querySelectorAll('.btn-copy-hex').forEach(button => {
                    button.addEventListener('click', function() {
                        const hexCodeContainer = this.closest('.hex-code-container');
                        const hexCode = hexCodeContainer.querySelector('.hex-code').textContent;
                        const copiedMessage = hexCodeContainer.querySelector('.copied-message');

                        navigator.clipboard.writeText(hexCode).then(() => {
                            copiedMessage.style.display = 'inline';
                            setTimeout(() => {
                                copiedMessage.style.display = 'none';
                            }, 2000);
                        });
                    });
                });
            });

            // CSS animation for color change
            const style = document.createElement('style');
            style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0,0,0,0.1); }
        50% { transform: scale(1.02); box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0,0,0,0); }
    }
    .ripple {
        position: absolute;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.5);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }
    @keyframes ripple {
        to { transform: scale(2.5); opacity: 0; }
    }
`;
            document.head.appendChild(style);
        </script>
    </div>
</x-tabnav>
