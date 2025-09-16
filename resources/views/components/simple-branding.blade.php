@php
    $user = Auth::user();
    $branding = $user ? $user->getBrandingWithInheritance() : [];
    
    $primaryColor = $branding['primary_color'] ?? '#3B82F6';
    $secondaryColor = $branding['secondary_color'] ?? '#6B7280';
    $accentColor = $branding['accent_color'] ?? '#10B981';
    $textColor = $branding['text_color'] ?? '#1F2937';
    $backgroundColor = $branding['background_color'] ?? '#FFFFFF';
    
    $primaryRgb = sscanf($primaryColor, "#%02x%02x%02x");
    $primaryLight = sprintf("rgba(%d, %d, %d, 0.1)", $primaryRgb[0], $primaryRgb[1], $primaryRgb[2]);
    $primaryDark = sprintf("#%02x%02x%02x", 
        max(0, $primaryRgb[0] - 30),
        max(0, $primaryRgb[1] - 30), 
        max(0, $primaryRgb[2] - 30)
    );
    $primaryBrightness = ($primaryRgb[0] * 299 + $primaryRgb[1] * 587 + $primaryRgb[2] * 114) / 1000;
    $primaryText = $primaryBrightness > 128 ? '#000000' : '#FFFFFF';
@endphp

<style>
:root {
    --primary-color: {{ $primaryColor }};
    --secondary-color: {{ $secondaryColor }};
    --accent-color: {{ $accentColor }};
    --text-color: {{ $textColor }};
    --background-color: {{ $backgroundColor }};
    --primary-light: {{ $primaryLight }};
    --primary-dark: {{ $primaryDark }};
    --primary-text: {{ $primaryText }};
}
</style>