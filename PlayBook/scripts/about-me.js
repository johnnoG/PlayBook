function profile() {
  location.reload();
}

function editProfile() {
  document.querySelectorAll(".data").forEach((el) => {
    el.setAttribute("contenteditable", "true");
    el.style.border = "1px solid #ccc";
  });
  document.getElementById("edit").style.display = "none";
  document.getElementById("saveChanges").style.display = "inline-block";
}

function saveChanges() {
  let updatedData = {
    FullName: document.getElementById("about").innerText.trim(),
    Birthday: document.getElementById("birthday").innerText.trim(),
    City: document.getElementById("city").innerText.trim(),
    PreferredPosition: document
      .getElementById("preferred-position")
      .innerText.trim(),
    Email: document.getElementById("email").innerText.trim(),
    Phone: document.getElementById("phone").innerText.trim(),
    StrongFoot: document.getElementById("strong-foot").innerText.trim(),
    Picture: document.getElementById("profile-picture").src.trim(),
    Rating: document.getElementById("rating").innerText.trim(),
  };

  fetch("../services/update-profile.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(updatedData),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        alert("Profile updated successfully");
      } else {
        alert("Failed to update profile: " + data.message);
      }
      window.location.href = "../services/player-profile.php"; // Redirect after saving
    })
    .catch((error) => console.error("Error:", error));
}
