document.addEventListener("DOMContentLoaded", function() {
    var vendorRequestLink = document.getElementById("vendorRequestLink");
    if (vendorRequestLink) {
        vendorRequestLink.addEventListener('click', function(event) {
            event.preventDefault(); 
            window.location.href = "RequestToVendor.html";
        });
    }
});
document.addEventListener("DOMContentLoaded", function() {
    var registrationForm = document.getElementById("registrationForm");
    if (registrationForm) {
        registrationForm.addEventListener("submit", function(event) {
            event.preventDefault(); 
            alert("The submission was successful!");
        });
    }
});

var paymentMethodInput = document.getElementById("PaymentMethod");
var additionalBox = document.getElementById("additionalBox");

paymentMethodInput.addEventListener("click", function() {

  if (additionalBox.style.display === "none") {
    additionalBox.style.display = "block";
  } else {
    additionalBox.style.display = "none";
  }
});
