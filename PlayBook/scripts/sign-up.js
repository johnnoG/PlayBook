var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form ...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  // ... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";

  }
  if (n == x.length - 1) {
    document.getElementById("nextBtn").innerHTML = "Submit";
    document.getElementById("toSub").style.display="inline";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  // ... and run a function that displays the correct step indicator:
  fixStepIndicator(n);
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form... :
  if (currentTab >= x.length) {
    //...the form gets submitted:
    document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x,
    y,
    i,
    valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false:
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i,
    x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class to the current step:
  x[n].className += " active";
}

var slider = document.getElementById("myRange");
var output = document.getElementById("demo");
output.innerHTML = slider.value;

slider.oninput = function() {
  output.innerHTML = this.value;
}


function ifsubmit() {
    let nxtbtn = document.getElementById("nextBtn");
    
    if (nxtbtn.innerHTML == "Submit") {
            let regForm = document.getElementById("regForm");
            let inp = regForm.querySelectorAll('input');
            let sel = regForm.querySelectorAll('select');
            let data = new FormData();

            inp.forEach(i => {
                if (i.type === 'file') {
                    data.append(i.name, i.files[0]);
                } else {
                    data.append(i.name, i.value);
                }
            });

            sel.forEach(j => {
                data.append(j.name, j.value);
            });

            // Log the FormData content
            for (let pair of data.entries()) {
                console.log(`${pair[0]}: ${pair[1]}`);
            }

            fetch('../services/sign-up.php', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                console.log("Data sent:", data);
                if (data.success) {
                    alert('User registered successfully.');
                    window.location.href = '../pages/main-page.html';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error sending data:', error);
            });

    }
}


