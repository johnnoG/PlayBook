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

function logIn() {
  //TODO: send to backend to store in db
  const email = validateEmail(document.getElementById("login-email").value);

  const password = document.getElementById("login-password").value;
}

const validateEmail = (email) => {
  return String(email)
    .toLowerCase()
    .match(
      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
};

function needToLog(){
  alert("You need to log in!");
}
