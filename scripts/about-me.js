document.addEventListener("DOMContentLoaded", function () {
  const email = "user@example.com"; // Replace with the actual user's email or obtain it dynamically
  fetch(`../services/player-profile.php?email=${email}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const user = data.data;
        document.getElementById("position").textContent =
          user.PreferredPosition;
        document.getElementById("about").textContent = user.FullName;
        document.getElementById("birthday").textContent = user.Birthday;
        document.getElementById("age").textContent = user.Age;
        document.getElementById("city").textContent = user.City;
        document.getElementById("preferred-position").textContent =
          user.PreferredPosition;
        document.getElementById("email").textContent = user.Email;
        document.getElementById("phone").textContent = user.Phone;
        document.getElementById("strong-foot").textContent = user.StrongFoot;
        document.getElementById("gender").textContent = user.Gender;
        document.getElementById("profile-picture").src =
          user.Picture || "https://bootdey.com/img/Content/avatar/avatar7.png";
        document.getElementById("rating").textContent = user.Rating;
      } else {
        console.error("Failed to fetch user data:", data.message);
      }
    })
    .catch((error) => console.error("Error fetching user data:", error));
});

function profile() {
  location.reload();
}

function edit() {
  let arrP = document.querySelectorAll(".data");
  let ed = document.getElementById("edit");
  let save = document.getElementById("saveChanges");
  for (let i = 0; i < arrP.length; i++) {
    let textB = document.createElement("input");
    textB.type = "text";
    textB.placeholder = "enter new info";
    textB.classList.add("new_text");
    textB.value = arrP[i].textContent;
    arrP[i].innerHTML = "";
    arrP[i].appendChild(textB);
  }
  ed.style.display = "none";
  save.style.display = "block";
}

function saveChanges() {
  let ed = document.getElementById("edit");
  let save = document.getElementById("saveChanges");
  let newtxt = document.querySelectorAll(".new_text");
  let arrP = document.querySelectorAll(".data");
  for (let i = 0; i < arrP.length; i++) {
    arrP[i].innerHTML = newtxt[i].value;
  }
  ed.style.display = "block";
  save.style.display = "none";
}
