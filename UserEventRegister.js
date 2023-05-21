var ticketQuantityInput = document.getElementById("ticketQuantity");
var totalCostOutput = document.getElementById("totalCost");

ticketQuantityInput.addEventListener("input", function() {
    var ticketQuantity = parseInt(ticketQuantityInput.value);
    var ticketPrice = 15; 
    var totalCost = ticketQuantity ? ticketQuantity * ticketPrice : 0;
    totalCostOutput.textContent = totalCost.toFixed(2);
});

document.getElementById("registrationForm").addEventListener("submit", function(event) {
    event.preventDefault(); 
    var name = document.getElementById("name").value;
    var surname = document.getElementById("surname").value;
    var eventName = document.getElementById("eventName").value;
    var ticketQuantity = parseInt(ticketQuantityInput.value);
    var ticketPrice = 15; // Assuming each ticket costs $15
    var totalCost = ticketQuantity ? ticketQuantity * ticketPrice : 0;
    var queryParams = new URLSearchParams({
        name: name,
        surname: surname,
        eventName: eventName,
        ticketQuantity: ticketQuantity,
        totalCost: totalCost.toFixed(2)
    });
    window.location.href = "ticket.html?" + queryParams.toString();
});
