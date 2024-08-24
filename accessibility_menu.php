<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .accessibility-menu {
            position: fixed;
            top: 90px;
            right: 10px;
            z-index: 1000;
        }

        .accessibility-toggle img {
            width: 40px;
            height: auto;
            background-color: transparent;
            border: none;
        }

        .accessibility-options {
            display: none;
            background-color: white;
            border-radius: 5px;
            padding: 10px;
            width: 200px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .accessibility-options h2 {
            margin: 0;
            font-size: 1em;
        }

        .accessibility-option {
            display: block;
            margin: 5px 0;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 0.9em;
        }

        .accessibility-option:hover {
            text-decoration: underline;
        }

        body.grayscale {
            filter: grayscale(100%);
        }

        body.high-contrast {
            filter: contrast(150%);
        }

        body.negative-contrast {
            filter: invert(100%);
            background: black;
        }

        body.highlight-links a {
            background-color: yellow;
            color: blue;
        }

        body.readable-font {
            font-family: Arial, sans-serif;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const accessibilityToggle = document.getElementById('accessibility-toggle');
    const accessibilityOptions = document.getElementById('accessibility-options');
    const body = document.body;

    accessibilityToggle.addEventListener('click', function () {
        accessibilityOptions.style.display = accessibilityOptions.style.display === 'block' ? 'none' : 'block';
    });

    document.getElementById('increase-text').addEventListener('click', function () {
        body.style.fontSize = (parseInt(window.getComputedStyle(body).fontSize) + 2) + 'px';
    });

    document.getElementById('decrease-text').addEventListener('click', function () {
        body.style.fontSize = (parseInt(window.getComputedStyle(body).fontSize) - 2) + 'px';
    });

    document.getElementById('grayscale').addEventListener('click', function () {
        body.classList.toggle('grayscale');
    });

    document.getElementById('high-contrast').addEventListener('click', function () {
        body.classList.toggle('high-contrast');
    });

    document.getElementById('negative-contrast').addEventListener('click', function () {
        body.classList.toggle('negative-contrast');
    });

    document.getElementById('highlight-links').addEventListener('click', function () {
        body.classList.toggle('highlight-links');
    });

    document.getElementById('readable-font').addEventListener('click', function () {
        body.classList.toggle('readable-font');
    });

    document.getElementById('reset').addEventListener('click', function () {
        body.removeAttribute('style');
        body.classList.remove('grayscale', 'high-contrast', 'negative-contrast', 'dark-background', 'highlight-links', 'readable-font');
    });
});

    </script>
</head>
<body>
    <div id="accessibility-menu" class="accessibility-menu">
        <button id="accessibility-toggle" class="accessibility-toggle">
            <img src="<?php echo (strpos($_SERVER['REQUEST_URI'], 'userProfile') !== false || strpos($_SERVER['REQUEST_URI'], 'admin') !== false) ? '../assets/images/accessibility.png' : 'assets/images/accessibility.png'; ?>" alt="Accessibility Icon">
        </button>
        <div id="accessibility-options" class="accessibility-options">
        <h2>Accessibility Tools</h2>
            <button id="increase-text" class="accessibility-option">Increase Text</button>
            <button id="decrease-text" class="accessibility-option">Decrease Text</button>
            <button id="grayscale" class="accessibility-option">Grayscale</button>
            <button id="high-contrast" class="accessibility-option">High Contrast</button>
            <button id="negative-contrast" class="accessibility-option">Negative Contrast</button>
            <button id="highlight-links" class="accessibility-option">Highlight Links</button>
            <button id="readable-font" class="accessibility-option">Readable Font</button>
            <button id="reset" class="accessibility-option">Reset</button>
        </div>
    </div>
</body>
</html>
