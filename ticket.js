var queryParams = new URLSearchParams(window.location.search);


var eventName = queryParams.get("eventName");
var name = queryParams.get("name");
var surname = queryParams.get("surname");
var ticketQuantity = queryParams.get("ticketQuantity");
var totalCost = queryParams.get("totalCost");

document.getElementById("eventName").textContent = eventName;
document.getElementById("name").textContent = name;
document.getElementById("surname").textContent = surname;
document.getElementById("ticketQuantity").textContent = ticketQuantity;
document.getElementById("totalCost").textContent = totalCost;
