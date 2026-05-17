/**
 * ROLE 3 — DELIVERY AGENT (AJAX)
 * Polls every 15 seconds for new available assignments while agent is online.
 * Shows notification bar without reloading the page.
 */

(function () {
    var pollInterval   = 15000; // 15 seconds
    var lastOrderId    = null;

    function poll() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "../../api/agent/new_assignment_notify.php", true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState != 4 || xhr.status != 200) return;
            try {
                var res = JSON.parse(xhr.responseText);
                if (res.has_new && res.assignment) {
                    // Only show if it is a new order we haven't shown yet
                    if (res.assignment.order_id != lastOrderId) {
                        lastOrderId = res.assignment.order_id;
                        showNotification(res.assignment);
                    }
                }
            } catch (e) {
                // Silently ignore
            }
        };

        xhr.send();
    }

    function showNotification(assignment) {
        var bar = document.getElementById("notif-bar");
        var msg = document.getElementById("notif-msg");
        if (!bar || !msg) return;

        msg.textContent = "New delivery from " + assignment.restaurant_name
            + " — ৳" + parseFloat(assignment.total_amount).toFixed(2)
            + ". Click View Orders to accept!";

        bar.style.display = "block";

        // Auto-hide after 30 seconds
        setTimeout(function () {
            bar.style.display = "none";
        }, 30000);
    }

    // Start polling immediately then every 15s
    poll();
    setInterval(poll, pollInterval);

})();
