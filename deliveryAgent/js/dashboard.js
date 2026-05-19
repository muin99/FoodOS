function postForm(url, data) {
    return fetch(url, {
        method: 'POST',
        body: data
    }).then(function (response) {
        return response.json();
    });
}

document.querySelectorAll('.accept-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        const data = new FormData();
        data.append('order_id', button.dataset.orderId);
        button.disabled = true;

        postForm('../controller/agentaceptsorders.php', data).then(function (result) {
            if (result.success) {
                location.reload();
                return;
            }
            alert(result.message || 'Could not accept order.');
            button.disabled = false;
        });
    });
});

document.querySelectorAll('.status-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        const data = new FormData();
        data.append('order_id', button.dataset.orderId);
        data.append('new_status', button.dataset.status);
        button.disabled = true;

        postForm('../controller/agentupdatestatus.php', data).then(function (result) {
            if (result.success) {
                location.reload();
                return;
            }
            alert(result.message || 'Could not update status.');
            button.disabled = false;
        });
    });
});

const onlineToggle = document.getElementById('onlineToggle');
if (onlineToggle) {
    onlineToggle.addEventListener('change', function () {
        const data = new FormData();
        data.append('is_online', onlineToggle.checked ? '1' : '0');
        onlineToggle.disabled = true;

        postForm('../controller/agentonline.php', data).then(function (result) {
            onlineToggle.disabled = false;
            if (!result.success) {
                onlineToggle.checked = !onlineToggle.checked;
                alert(result.message || 'Could not update online status.');
                return;
            }
            const statusText = result.is_online == 1 ? 'Online' : 'Offline';
            document.getElementById('onlineText').textContent = statusText;
            const statusCardText = document.getElementById('statusCardText');
            if (statusCardText) {
                statusCardText.textContent = statusText;
            }
        }).catch(function () {
            onlineToggle.disabled = false;
            onlineToggle.checked = !onlineToggle.checked;
            alert('Could not update online status.');
        });
    });
}

const profileForm = document.getElementById('profileForm');
if (profileForm) {
    profileForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const message = document.getElementById('profileMessage');

        postForm('../controller/agentupdateprofile.php', new FormData(profileForm)).then(function (result) {
            message.textContent = result.success ? 'Profile saved.' : (result.message || 'Profile update failed.');
        });
    });
}
