 // Reset form function
        function resetForm() {
            if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
                document.getElementById('blotterForm').reset();
            }
        }

        // Set today's date as default
        document.getElementById('when').valueAsDate = new Date();