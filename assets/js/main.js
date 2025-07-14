$(document).ready(function () {

   if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('service-worker.js').then((registration) => {
        console.log('ServiceWorker registration successful with scope: ', registration.scope);
      }, (error) => {
        console.log('ServiceWorker registration failed: ', error);
      });
    });
  }
  

  $("#systemLogo").on("change", function (event) {
    const fileInput = event.target;
    const preview = $(".preview");

    preview.empty();

    const files = fileInput.files;
    for (const file of files) {
      if (file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.attr("src", e.target.result);
          $("input[name=system_logo]").attr("value", e.target.result);
        };
        reader.readAsDataURL(file);
      } else {
        const para = $("<p>").text(
          `File ${file.name} is not a valid image file.`
        );
        preview.append(para);
      }
    }
  });

  $("#profile").on("change", function (event) {
    const fileInput = event.target;
    const preview = $(".profilePict");

    preview.empty();

    const files = fileInput.files;
    for (const file of files) {
      if (file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.attr("src", e.target.result);
          $("input[name=user_profile]").attr("value", e.target.result);
        };
        reader.readAsDataURL(file);
      } else {
        const para = $("<p>").text(
          `File ${file.name} is not a valid image file.`
        );
        preview.append(para);
      }
    }
  });


  $("body").on("submit", "#install-form", function (e) {
    e.preventDefault();
    $this = $(this);
    const inputs = $("input");
    const data = new FormData();
    data.append("action", "save_installation_data");
    const fileInput = $(".custom-file-input");
    for (let i = 0; i < inputs.length; i++) {
      if (
        $(inputs[i]).attr("name") &&
        $(inputs[i]).attr("name") != "system_logo"
      ) {
        data.append($(inputs[i]).attr("name"), $(inputs[i]).val());
      }
    }
    const selectedFile = fileInput[0].files[0];
    if (selectedFile) {
      data.append("site_logo", selectedFile);
    }
    if (!$this.hasClass("processing")) {
      $this.addClass("processing");
      $.ajax({
        url: base_url + "/auth/ajax.php", 
        method: "POST", 
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function (xhr) {
          $this.find("button").text("Processing...");
        },
        success: function (response) {
          $this.removeClass("processing");
          $this.find("button").text("Successfully");
          if (response.success) {
            Swal.fire({
              title: "Nice!",
              text:
                response.message + ", Redirecting you to login in 5 seconds.",
              icon: "success",
              timer: 5000,
              showConfirmButton: false, 
            }).then(() => {
              window.location.href = base_url+"src/";
            });
          } else {
            $this.removeClass("processing");
            $this.find("button").text("Failed to save!");
            console.log(response);
            Swal.fire({
              title: "Error",
              text: response.message,
              icon : "error",
              timer : 3000,
            }).then (()=>{
              window.location.href = base_url +"/installer/";
            });
          }
        },
        error: function (error) {
          $this.removeClass("processing");
          $this.find("button").text("Please try again!");
        },
      });
    }
  });


$("body").on("submit", "#login-form", function (e) {
    e.preventDefault();
    const $this = $(this);
    const username = $this.find("input[name=username]").val();
    const password = $this.find("input[name=password]").val();

    if (!$this.hasClass("processing")) {
        $this.addClass("processing");
        $.ajax({
            url: base_url + "auth/Ajax.php",
            method: "POST",
            data: {
                action: "login", 
                username: username,
                password: password,
            },
            beforeSend: function () {
                $this.find("button").text("Processing...");
            },
            success: function (response) {
                $this.removeClass("processing");

                console.log("Response received:", response); 

                if (typeof response === "string") {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        console.error("Invalid JSON:", response);
                        $this.find("button").text("Login");
                        return;
                    }
                }

                console.log("Parsed Response:", response); 

                if (response.success) {
                    $this.find("button").text("Login Success!");
                    Swal.fire({
                        title: "Logged In!",
                        text: response.message + ", Redirecting...",
                        icon: "success",
                        timer: 3000,
                        showConfirmButton: false,
                    }).then(() => {
                        console.log("Redirecting, Role:", response.role); 

                        if (response.role === "student") {
                            console.log("Redirecting to student dashboard"); 
                            window.location.href = base_url + "src/student/dashboard.php";
                        } else if (response.role === "admin") {
                            console.log("Redirecting to admin dashboard"); 
                            window.location.href = base_url + "src/admin/dashboard.php";
                        } else {
                            console.log("Redirecting to index"); 
                            window.location.href = base_url + "index.php";
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.message,
                        icon: "error",
                        timer: 3000,
                    }).then(() => {
                        $this.find("input[name=username]").val("");
                        $this.find("input[name=password]").val("");
                        $this.find("button").text("Please try again!");
                    });
                }
            },
            error: function (error) {
                $this.removeClass("processing");
                $this.find("button").text("Please try again!");
                console.error("Ajax Error:", error);
            },
        });
    }
});

});
