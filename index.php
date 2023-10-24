<!DOCTYPE html>
<html>
<head>
    <title>Sign Language Images</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
    <style>
        #sentence {
            width: 100%;
            height: 70vh;
            outline: none;
            border: none;
            padding: 30px;
            font-size: 2rem;
            background: #f7f7f7;
            resize: none;
            border-radius: 5px;
            font-family: UberMove;
        }

        #image-container {
            margin: 0;
            padding: 10px;
            background: #000;
            height: auto;
            bottom: 0;
            border-radius: 5px;
        }

        #image-container img {
            width: 50px; /* Adjust the size as needed */
            height: auto;
        }

        #image-container .space {
            width: 30px; /* Adjust the size as needed */
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <textarea id="sentence" class="form-control" required></textarea>
                <button class="btn btn-primary mt-2" id="import-button"><i class="fas fa-file-import"></i> Import File</button>
                <button class="btn btn-success mt-2" id="save-button"><i class="fas fa-save"></i> Save</button>
                <button class="btn btn-info mt-2" id="voice-button"><i class="fas fa-microphone"></i> Voice</button>
                <input type="file" id="file-input" style="display: none;">
            </div>
            <div class="col-md-6">
                <div id="image-container" class="text-center">
                    <!-- The images will be displayed here -->
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
    </script>
</body>
</html>
