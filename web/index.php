<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

$qrText = $_POST['text'] ?? 'https://qr-code.aaacoder.xyz';
$qrSize = (int)($_POST['size'] ?? 300);
$qrLabel = $_POST['label'] ?? '';
$qrFormat = $_POST['format'] ?? 'png';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
    try {
        $originalFormat = $qrFormat;
        $formatFallback = false;
        
        // Check WebP support before attempting to use it
        if ($qrFormat === 'webp' && !function_exists('imagewebp')) {
            $qrFormat = 'png'; // Fallback to PNG
            $formatFallback = true;
        }
        
        $writerClass = match($qrFormat) {
            'png' => \Endroid\QrCode\Writer\PngWriter::class,
            'svg' => \Endroid\QrCode\Writer\SvgWriter::class,
            'webp' => \Endroid\QrCode\Writer\WebPWriter::class,
            default => \Endroid\QrCode\Writer\PngWriter::class
        };
        
        $builder = new Builder(
            writer: new $writerClass(),
            data: $qrText,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $qrSize,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            labelText: $qrLabel,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );
        
        $result = $builder->build();

        if ($qrFormat === 'svg') {
            $qrImage = $result->getString();
            $mimeType = 'image/svg+xml';
            $fileExtension = 'svg';
        } else {
            $qrImage = base64_encode($result->getString());
            $mimeType = $qrFormat === 'webp' ? 'image/webp' : 'image/png';
            $fileExtension = $qrFormat;
        }
        
        // Show fallback message if format was changed
        if ($formatFallback) {
            $fallbackMessage = "WebP format not supported on this server. Generated as PNG instead.";
        }
    } catch (Exception $e) {
        $error = 'Error generating QR code: ' . $e->getMessage();
    }
}

// Generate structured data for SEO
$structuredData = [
    "@context" => "https://schema.org",
    "@type" => "WebApplication",
    "name" => "QR Code Generator",
    "description" => "Free online QR code generator. Create custom QR codes for URLs, text, contact info, and more instantly.",
    "url" => "https://qr-code.aaacoder.xyz",
    "applicationCategory" => "UtilityApplication",
    "operatingSystem" => "Any",
    "offers" => [
        "@type" => "Offer",
        "price" => "0",
        "priceCurrency" => "USD"
    ],
    "creator" => [
        "@type" => "Organization",
        "name" => "AAACoder",
        "url" => "https://aaacoder.xyz"
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free QR Code Generator - Create Custom QR Codes Online | AAACoder</title>
    <meta name="description" content="Generate free QR codes online instantly. Create custom QR codes for URLs, text, contact info, WiFi, business cards, and more. No signup required. Download in PNG, SVG, or WebP formats.">
    <meta name="keywords" content="QR code generator, free QR code, online QR code maker, QR code creator, barcode generator, QR code tool, custom QR codes, URL to QR code, text to QR code, contact QR code, WiFi QR code, business card QR code">
    <meta name="author" content="AAACoder">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://qr-code.aaacoder.xyz/">
    <meta property="og:title" content="Free QR Code Generator - Create Custom QR Codes Online">
    <meta property="og:description" content="Generate free QR codes online instantly. Create custom QR codes for URLs, text, contact info, WiFi, business cards, and more. No signup required.">
    <meta property="og:image" content="https://qr-code.aaacoder.xyz/assets/qr-code-preview.png">
    <meta property="og:site_name" content="QR Code Generator">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://qr-code.aaacoder.xyz/">
    <meta property="twitter:title" content="Free QR Code Generator - Create Custom QR Codes Online">
    <meta property="twitter:description" content="Generate free QR codes online instantly. Create custom QR codes for URLs, text, contact info, WiFi, business cards, and more. No signup required.">
    <meta property="twitter:image" content="https://qr-code.aaacoder.xyz/assets/qr-code-preview.png">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://qr-code.aaacoder.xyz/">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Analytics -->
    <script defer data-domain="qr-code.aaacoder.xyz" src="https://plausible.aaacoder.xyz/js/script.js"></script>
    <script id="aclib" type="text/javascript" src="//acscdn.com/script/aclib.js"></script>
    <script type="text/javascript">
        aclib.runAutoTag({
            zoneId: 'eyrol6srqv',
        });
    </script>
    
    <!-- Structured Data -->
    <script type="application/ld+json">
        <?php echo json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
    </script>
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .input-focus {
            transition: all 0.2s ease;
        }
        .input-focus:focus {
            transform: scale(1.02);
        }
        .qr-preview {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen font-sans">
    <!-- Header -->
    <header class="gradient-bg text-white">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">QR Code Generator</h1>
                <p class="text-xl text-blue-100 mb-6">Create custom QR codes instantly for free</p>
                <div class="flex justify-center space-x-4 text-sm">
                    <span class="bg-white/20 px-3 py-1 rounded-full">✓ No Registration</span>
                    <span class="bg-white/20 px-3 py-1 rounded-full">✓ Instant Generation</span>
                    <span class="bg-white/20 px-3 py-1 rounded-full">✓ Multiple Formats</span>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <!-- Main Generator Card -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 card-hover">
                <form method="POST" class="space-y-6">
                    <!-- Text Input -->
                    <div>
                        <label for="text" class="block text-lg font-semibold text-gray-800 mb-3">
                            <span class="text-primary-600">📝</span> Text or URL to encode
                        </label>
                        <textarea 
                            id="text" 
                            name="text" 
                            rows="4" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-100 input-focus resize-none"
                            placeholder="Enter text, URL, contact info, WiFi details, or any data you want to encode..."
                            required><?php echo htmlspecialchars($qrText); ?></textarea>
                        <p class="text-sm text-gray-500 mt-2">Examples: https://example.com, Contact info, WiFi:SSID:Password</p>
                    </div>

                    <!-- Options Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="size" class="block text-lg font-semibold text-gray-800 mb-3">
                                <span class="text-primary-600">📏</span> Size (px)
                            </label>
                            <input 
                                type="number" 
                                id="size" 
                                name="size" 
                                min="100" 
                                max="1000" 
                                value="<?php echo $qrSize; ?>"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-100 input-focus">
                        </div>
                        <div>
                            <label for="format" class="block text-lg font-semibold text-gray-800 mb-3">
                                <span class="text-primary-600">🖼️</span> Format
                            </label>
                            <select 
                                id="format" 
                                name="format"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-100 input-focus">
                                <option value="png" <?php echo $qrFormat === 'png' ? 'selected' : ''; ?>>PNG (Best Quality)</option>
                                <option value="svg" <?php echo $qrFormat === 'svg' ? 'selected' : ''; ?>>SVG (Scalable)</option>
                                <?php if (function_exists('imagewebp')): ?>
                                <option value="webp" <?php echo $qrFormat === 'webp' ? 'selected' : ''; ?>>WebP (Optimized)</option>
                                <?php endif; ?>
                            </select>
                            <?php if (!function_exists('imagewebp')): ?>
                            <p class="text-sm text-gray-500 mt-2">💡 WebP format not available on this server</p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label for="label" class="block text-lg font-semibold text-gray-800 mb-3">
                                <span class="text-primary-600">🏷️</span> Label (Optional)
                            </label>
                            <input 
                                type="text" 
                                id="label" 
                                name="label" 
                                value="<?php echo htmlspecialchars($qrLabel); ?>"
                                placeholder="QR Code Label"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-100 input-focus">
                        </div>
                    </div>

                    <!-- Generate Button -->
                    <button 
                        type="submit" 
                        name="generate"
                        class="w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <span class="flex items-center justify-center">
                            <span class="mr-2">⚡</span>
                            Generate QR Code
                        </span>
                    </button>
                </form>

                <!-- QR Code Result -->
                <?php if (isset($qrImage)): ?>
                    <div class="mt-8 qr-preview">
                        <div class="text-center">
                            <?php if (isset($fallbackMessage)): ?>
                                <div class="mb-4 p-4 bg-yellow-50 border-2 border-yellow-200 text-yellow-700 rounded-xl">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">⚠️</span>
                                        <div>
                                            <h4 class="font-semibold">Format Fallback</h4>
                                            <p><?php echo htmlspecialchars($fallbackMessage); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">Your QR Code is Ready! 🎉</h3>
                            <div class="bg-gray-50 rounded-2xl p-8 inline-block">
                                <?php if ($qrFormat === 'svg'): ?>
                                    <div class="max-w-xs mx-auto"><?php echo $qrImage; ?></div>
                                <?php else: ?>
                                    <img src="data:<?php echo $mimeType; ?>;base64,<?php echo $qrImage; ?>" 
                                         alt="Generated QR Code" 
                                         class="mx-auto border-2 border-gray-200 rounded-lg shadow-lg">
                                <?php endif; ?>
                            </div>
                            <div class="mt-6 space-y-3">
                                <a 
                                    href="<?php echo $qrFormat === 'svg' ? 'data:image/svg+xml;base64,' . base64_encode($qrImage) : 'data:' . $mimeType . ';base64,' . $qrImage; ?>" 
                                    download="qrcode.<?php echo $fileExtension; ?>"
                                    class="inline-block bg-green-600 text-white py-3 px-6 rounded-xl font-semibold hover:bg-green-700 transition duration-200 transform hover:scale-105 shadow-lg">
                                    <span class="flex items-center">
                                        <span class="mr-2">⬇️</span>
                                        Download QR Code
                                    </span>
                                </a>
                                <div class="text-sm text-gray-600">
                                    Format: <?php echo strtoupper($qrFormat); ?> | Size: <?php echo $qrSize; ?>px
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif (isset($error)): ?>
                    <div class="mt-6 p-6 bg-red-50 border-2 border-red-200 text-red-700 rounded-xl">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">⚠️</span>
                            <div>
                                <h4 class="font-semibold">Error Generating QR Code</h4>
                                <p><?php echo htmlspecialchars($error); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Features Section -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-lg card-hover">
                    <div class="text-4xl mb-4">🚀</div>
                    <h3 class="text-xl font-semibold mb-2">Instant Generation</h3>
                    <p class="text-gray-600">Generate QR codes instantly without any registration or waiting time.</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg card-hover">
                    <div class="text-4xl mb-4">🎨</div>
                    <h3 class="text-xl font-semibold mb-2">Multiple Formats</h3>
                    <p class="text-gray-600">Download your QR codes in PNG, SVG, or WebP formats for any use case.</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg card-hover">
                    <div class="text-4xl mb-4">🔒</div>
                    <h3 class="text-xl font-semibold mb-2">Privacy First</h3>
                    <p class="text-gray-600">Your data is processed locally and never stored on our servers.</p>
                </div>
            </div>

            <!-- Use Cases Section -->
            <div class="mt-12 bg-white rounded-2xl p-8 shadow-lg">
                <h2 class="text-3xl font-bold text-center mb-8">Popular Use Cases</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center p-4 rounded-lg bg-blue-50">
                        <div class="text-2xl mb-2">🌐</div>
                        <h4 class="font-semibold">Website URLs</h4>
                    </div>
                    <div class="text-center p-4 rounded-lg bg-green-50">
                        <div class="text-2xl mb-2">📱</div>
                        <h4 class="font-semibold">Contact Info</h4>
                    </div>
                    <div class="text-center p-4 rounded-lg bg-purple-50">
                        <div class="text-2xl mb-2">📶</div>
                        <h4 class="font-semibold">WiFi Networks</h4>
                    </div>
                    <div class="text-center p-4 rounded-lg bg-orange-50">
                        <div class="text-2xl mb-2">💼</div>
                        <h4 class="font-semibold">Business Cards</h4>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="text-center">
                <p class="mb-4">
                    <strong>Keywords:</strong> free QR code generator, online QR code maker, custom QR codes, 
                    barcode creator, QR code tool, URL to QR code, text to QR code, contact QR code, 
                    WiFi QR code generator, business card QR code, vCard QR code, email QR code, 
                    SMS QR code generator, location QR code, event QR code, social media QR code
                </p>
                <p class="text-gray-400">&copy; 2024 QR Code Generator. Free tool provided by <a href="https://aaacoder.xyz" class="text-blue-400 hover:underline">AAACoder</a></p>
                <div class="mt-4">
                    <a href="https://dash.aaacoder.xyz/" class="text-blue-400 hover:text-blue-300 underline" target="_blank" rel="noopener">
                        Discover more utility tools at AAACoder Dashboard
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript for enhanced UX -->
    <script>
        // Auto-resize textarea
        const textarea = document.getElementById('text');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        }

        // Copy to clipboard functionality
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.classList.add('bg-green-600');
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                }, 2000);
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('SW registered: ', registration);
                    })
                    .catch(registrationError => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    </script>
</body>
</html>
