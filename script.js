function startStreaming() {
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var video = document.createElement('video');
    var hideButton = document.getElementById('hideButton');

    navigator.mediaDevices.getDisplayMedia({ video: true }).then(function (stream) {
        video.srcObject = stream;
        video.play();
        drawFrame();
    });

    function drawFrame() {
        if (!video.hidden) {
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
        }
        requestAnimationFrame(drawFrame);
    }

    hideButton.addEventListener('click', function () {
        video.hidden = !video.hidden;
        hideButton.textContent = video.hidden ? 'Show Video' : 'Hide Video';
    });
}

function setSelectedLocationId(selectElement) {
    const selectedIndex = selectElement.selectedIndex;
    const selectedValue = selectElement.options[selectedIndex].value;

    fetch('update_cache.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ selected_location_id: selectedValue })
    })
    .then(response => response.text())
    .then(data => {
        console.log('Cache updated with location ID:', selectedValue);
    })
    .catch(error => console.error('Error:', error));

    window.location.href = '?selected_location_id=' + selectedValue;
}
