function showInfo() {
  let sec1 = document.getElementById("sect1");
  let p1 = document.createElement("p");
  let div1 = document.createElement("div");
  div1.appendChild(p1);
  // div1.classList.add("displayDiv");

  p1.innerText =
    "Imagine arriving in an unfamiliar place and wanting to join a game of football or basketball. With PlayBook,you can easily find and join local games without the hassle of organizing participants.Register as a player, specify your position,preferred playing foot or hand, and other game details.PlayBook locates nearby fields and lets you sign up for games at specific times.Once enough players join, a game is automatically arranged.Players can rate each other and the facilities, creating a system that matches users by skill level. PlayBook makes it easy to enjoy spontaneous matches and connect with new people,wherever you are.";
  p1.classList.add("displayP");
  sec1.innerHTML = "";
  sec1.appendChild(p1);
  p1.addEventListener("mouseleave", closeInfo);
}

function closeInfo() {
  let sec1 = document.getElementById("sect1");
  sec1.innerHTML = `
      <div class="FMDiv">Playbook</div>
      <h3>Find.Book.Play</h3>
      <div class="mainDiv">
        <div><a href="#"> Sign up</a></div>
        <div id="popLearn">
          <a href="#" onclick="showInfo()">Learn more</a>
        </div>
      </div>
    `;
}
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}

function checkUserVerification() {
  // This function should call a PHP script to check if the user is verified
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "../services/check-verification.php", false);
  xhr.send(null);
  return xhr.responseText === "true";
}

function needToLog() {
  if (!checkUserVerification()) {
    alert("You need to log in!");
    window.location.href = "/PlayBook/pages/main-page.html";
  }
}

function navigateToPage(page) {
  if (checkUserVerification()) {
    window.location.href = page;
  } else {
    alert("You need to log in!");
    window.location.href = "/PlayBook/pages/main-page.html";
  }
}

