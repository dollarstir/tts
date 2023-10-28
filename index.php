<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Language Images</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
    <!-- ... (previous code) ... -->
<style>
    body {
        background-color: #f0f0f0;
        color: #333;
        font-family: Arial, sans-serif;
    }

    #page-content {
        background: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 10px;
        margin-top: 30px;
    }

    #sentence {
        width: 100%;
        height:50vh;
        outline: none;
        border: none;
        padding: 20px;
        font-size: 1.5rem;
        background: #f7f7f7;
        resize: none;
        border-radius: 10px;
        font-family: Arial, sans-serif;
        color: #333;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    #image-container {
        margin: 0;
        padding: 10px;
        background: #333;
        height: auto;
        border-radius: 5px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    #image-container img {
        width: 50px;
        height: auto;
        margin: 5px;
        position: relative;
    }

    #image-container .img-letter {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        font-size: 18px;
        color: #fff;
    }

    #image-container .space {
        width: 30px;
        display: inline-block;
    }

    #keyboard-container {
        background: #333;
        padding: 20px;
        border-radius: 10px;
    }

    #keyboard-heading {
        color: #fff;
    }

    #keyboard-container img {
        width: 50px;
        height: auto;
        cursor: pointer;
        margin: 5px;
        border: 2px solid #fff;
        border-radius: 50%;
        position: relative;
    }

    .keyboard-image:hover {
        background: #fff;
        border: 2px solid #333;
        border-radius: 50%;
    }

    .keyboard-letter {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: flex-end; /* Align text to the bottom */
        font-weight: bold;
        font-size: 18px;
        color: #fff;
        text-align: center;
    }

    .btn {
        font-size: 1rem;
    }
</style>
<!-- ... (remaining code) ... -->

</head>
<body>
    <div class="container mt-5" id="page-content">
        <div class="row">
            <div class="col-md-6">
                <textarea id="sentence" class="form-control" placeholder="Type your sentence here..." required></textarea>
                <button class="btn btn-primary mt-2" id="import-button"><i class="fas fa-file-import"></i> Import File</button>
                <button class="btn btn-success mt-2" id="save-button"><i class="fas fa-save"></i> Save</button>
                <button class="btn btn-info mt-2" id="voice-button"><i class="fas fa-microphone"></i> Voice</button>
                <input type="file" id="file-input" style="display: none;">
            </div>
            <div class="col-md-6">
                <div id="image-container" class="text-center">
                    <!-- The images will be displayed here -->
                </div>
                <h2 id="keyboard-heading" class="text-center mt-3" style="color:black;">Sign Language Keyboard</h2>
                <div id="keyboard-container" class="text-center mt-3">
                    <!-- The keyboard images will be displayed here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
    <script>
        // Your JavaScript code remains the same

         // Your JavaScript code remains the same

         function updateImages(content) {
            var sentence = content.toUpperCase();
            var imageContainer = $('#image-container');
            imageContainer.empty(); // Clear previous images

            for (var i = 0; i < sentence.length; i++) {
                var letter = sentence[i];
                if (letter === ' ') {
                    // Add a space element (non-breaking space) for better visualization
                    var space = $('<div class="space">&nbsp;</div>');
                    imageContainer.append(space);
                } else if (/^[A-Z]$/.test(letter)) {
                    var img = $('<img>');
                    img.attr('src', 'images/' + letter + '.webp');
                    img.attr('alt', letter);
                    var letterOverlay = $('<div class="img-letter">' + letter + '</div>');
                    img.append(letterOverlay); // Add the letter overlay to the image
                    imageContainer.append(img);
                }
            }
        }

        $(document).ready(function() {
            $('#sentence').on('input', function() {
                updateImages($(this).val());
            });

            $('#import-button').click(function() {
                // Reset the file input
                $('#file-input').val('');
                // Trigger the hidden file input
                $('#file-input').click();
            });

            $('#file-input').change(function(e) {
                var file = e.target.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    var fileContent = e.target.result;
                    $('#sentence').val(fileContent);
                    updateImages(fileContent);
                };
                reader.readAsText(file);
            });

            $('#save-button').click(function() {
                saveAsPDF();
            });

            // Initialize SpeechRecognition
            const recognition = new webkitSpeechRecognition() || new SpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;

            $('#voice-button').click(function() {
                if (recognition.isListening) {
                    // If it's already listening, stop listening and change the icon
                    recognition.stop();
                    $('#voice-button i').removeClass('fa-volume-up').addClass('fa-microphone');
                    $('#voice-button span').text('Voice');
                } else {
                    // Start voice recognition when the button is clicked
                    recognition.start();
                    $('#voice-button i').removeClass('fa-microphone').addClass('fa-volume-up');
                    $('#voice-button span').text('Listening');
                }
            });

            recognition.onresult = function(event) {
                // Get the recognized text
                const result = event.results[0][0].transcript;

                // Update the textarea with the recognized text
                $('#sentence').val(result);
                updateImages(result);
            };

            recognition.onend = function() {
                // Stop recognition when finished
                recognition.stop();
                $('#voice-button i').removeClass('fa-volume-up').addClass('fa-microphone');
                $('#voice-button span').text('Voice');
            };
        });

        function saveAsPDF() {
            // Capture the content of the image container as an image using html2canvas
            html2canvas(document.getElementById('image-container')).then(function(canvas) {
                var imgData = canvas.toDataURL('image/png');

                // Create a new jsPDF instance
                var pdf = new jsPDF("p", "mm", "a4");

                // Calculate the width and height of the PDF page
                var width = 100;
                var height = (canvas.height * width) / canvas.width;

                // Add the image to the PDF
                pdf.addImage(imgData, 'PNG', 0, 0, width, height);

                // Save the PDF
                pdf.save('sign_language_images.pdf');
            });
        }

        // Function to add keyboard images
        function addKeyboardImages() {
            var keyboardContainer = $('#keyboard-container');
            keyboardContainer.empty(); // Clear previous keyboard images

            // Add images for 'A' to 'Z' and a space
            for (var letter = 'A'; letter <= 'Z'; letter = String.fromCharCode(letter.charCodeAt(0) + 1)) {
                var img = $('<img>');
                img.attr('src', 'images/' + letter + '.webp');
                img.attr('alt', letter);
                img.addClass('keyboard-image');
                img.click(function () {
                    var letterClicked = $(this).attr('alt');
                    var currentText = $('#sentence').val();
                    $('#sentence').val(currentText + letterClicked);
                    updateImages(currentText + letterClicked);
                });
                keyboardContainer.append(img);
            }

            // Add a space image
            var spaceImg = $('<img>');
            spaceImg.attr('src', 'images/space.png');
            spaceImg.attr('alt', ' ');
            spaceImg.addClass('keyboard-image');
            spaceImg.click(function () {
                var currentText = $('#sentence').val();
                $('#sentence').val(currentText + ' ');
                updateImages(currentText + ' ');
            });
            keyboardContainer.append(spaceImg);

            // Add a backspace image
            var backspaceImg = $('<img>');
            backspaceImg.attr('src', 'images/backspace.png');
            backspaceImg.attr('alt', 'Backspace');
            backspaceImg.addClass('keyboard-image');
            backspaceImg.click(function () {
                var currentText = $('#sentence').val();
                $('#sentence').val(currentText.slice(0, -1)); // Remove the last character
                updateImages(currentText.slice(0, -1));
            });
            keyboardContainer.append(backspaceImg);
        }

        // Call the function to add keyboard images
        addKeyboardImages();
    </script>
</body>
</html>
