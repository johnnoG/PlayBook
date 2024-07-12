document.addEventListener("DOMContentLoaded", function () {
  fetchUserProfile();
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
  let updatedData = {};

  for (let i = 0; i < arrP.length; i++) {
    arrP[i].innerHTML = newtxt[i].value;
    let key = arrP[i].id;
    updatedData[key] = newtxt[i].value;
  }

  ed.style.display = "block";
  save.style.display = "none";

  fetch(
    "http://toharhermon959.byethost9.com/PlayBook/services/update-profile.php",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(updatedData),
    }
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        console.log("Profile updated successfully");
      } else {
        console.error("Failed to update profile:", data.message);
      }
    })
    .catch((error) => console.error("Error updating profile:", error));
}

function fetchUserProfile() {
  var xhr = new XMLHttpRequest();
  xhr.open(
    "POST",
    "http://toharhermon959.byethost9.com/PlayBook/services/player-profile.php",
    false
  );
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send();

  if (xhr.status === 200) {
    var response = JSON.parse(xhr.responseText);
    if (response.success) {
      const user = response.data;
      document.getElementById("position").textContent = user.PreferredPosition;
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
      console.error("Failed to fetch user data:", response.message);
    }
  } else {
    console.error("Error fetching user data:", xhr.statusText);
  }
}
