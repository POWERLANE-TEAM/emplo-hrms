// dropzone.config.js

Dropzone.options.dropzone = {
    paramName: "file", // The name that will be used to transfer the file
    maxFiles: 1, // Allow only one file at a time
    maxFilesize: 3, // Maximum file size in MB
    acceptedFiles: ".pdf", // Accept only PDF files
    clickable: '#dropzone', // Make the entire dropzone area clickable
    init: function() {
        // Prevent the default behavior when a file is dropped outside the dropzone
        document.body.addEventListener("dragover", function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        document.body.addEventListener("drop", function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        this.on("addedfile", function(file) {
            // Check the file size
            if (file.size > 3000000) { // 3MB in bytes
                this.removeFile(file);
                alert("File size exceeds the limit. Please upload a file less than 3MB.");
            }
        });

        this.on("error", function(file, message) {
            // Display an error if something goes wrong
            alert(message);
        });
    }
};