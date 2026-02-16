<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?><?= (isset($title) ? $title . ' - ' : '') ?>Video Permission App</title>
    
    <!-- Tailwind CSS v4 -->
    <link href="<?= base_url('assets/css/app.css?v=' . time()) ?>" rel="stylesheet">
    
    <!-- Alpine.js (Bundled) -->
    <script defer src="<?= base_url('assets/js/bundle.js') ?>"></script>
</head>
<body class="bg-white text-gray-900 font-sans antialiased">
    
    <div class="min-h-screen flex flex-col justify-center items-center">
        <?= $this->renderSection('content') ?>
    </div>

</body>
</html>
