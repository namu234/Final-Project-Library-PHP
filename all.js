document.addEventListener("DOMContentLoaded", function () {
  // Pop Up
  const closeBtn = document.getElementById("closePopupBtn");
  const popup = document.getElementById("popupLoginNotice");

  if (closeBtn && popup) {
    closeBtn.addEventListener("click", function () {
      popup.style.display = "none";
    });
  }

  // Sidebar
  const current = window.location.pathname.split("/").pop(); // untuk ambil nama file
  const links = document.querySelectorAll(".menu-item");

  links.forEach((link) => {
    if (link.getAttribute("href") === current) {
      link.classList.add("active");
    }
  });

  // Peminjaman
  document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("keywordUserPinjam");
    const hasil = document.getElementById("hasilUserPinjam");

    function muatPinjaman(keyword = "") {
      fetch(`daftar_ajax.php?keyword=${encodeURIComponent(keyword)}`)
        .then((res) => res.text())
        .then((html) => (hasil.innerHTML = html))
        .catch((err) => (hasil.innerHTML = "Gagal memuat."));
    }

    // Sinopsis
    function bukaModal(id_buku) {
      fetch("sinopsis.php?id_buku=" + id_buku)
        .then((response) => response.text())
        .then((data) => {
          document.getElementById("sinopsisDetail").innerHTML = data;
          document.getElementById("sinopsisModal").style.display = "block";
        });
    }

    function tutupModal() {
      document.getElementById("sinopsisModal").style.display = "none";
    }

    // Muat data awal
    muatPinjaman();

    // Jalankan saat user mengetik
    input.addEventListener("input", function () {
      muatPinjaman(this.value);
    });
  });

  function toggleSidebar() {
    const sidebar = document.querySelector(".sidebar");
    sidebar.classList.toggle("open");
  }

  document.addEventListener("click", function (e) {
    const dropdown = document.querySelector(".login-dropdown");
    const menu = document.querySelector(".dropdown-menu");

    if (dropdown.contains(e.target)) {
      menu.style.display = menu.style.display === "block" ? "none" : "block";
    } else {
      menu.style.display = "none";
    }
  });

  // DOM Elements
  const loginOverlay = document.getElementById("loginOverlay");
  const registerOverlay = document.getElementById("registerOverlay");

  const openLoginBtn = document.getElementById("openLogin");
  const closeLoginBtn = document.getElementById("closeLogin");
  const closeRegisterBtn = document.getElementById("closeRegister");

  const openRegisterFromLogin = document.getElementById(
    "openRegisterFromLogin"
  );
  const openLoginFromRegister = document.getElementById(
    "openLoginFromRegister"
  );

  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");

  // Overlay Control
  function openOverlay(overlay) {
    overlay.classList.add("active");
    document.body.style.overflow = "hidden";
  }

  function closeOverlay(overlay) {
    overlay.classList.remove("active");
    document.body.style.overflow = "";
  }

  // Event Listeners
  openLoginBtn?.addEventListener("click", () => openOverlay(loginOverlay));
  closeLoginBtn?.addEventListener("click", () => closeOverlay(loginOverlay));
  closeRegisterBtn?.addEventListener("click", () =>
    closeOverlay(registerOverlay)
  );

  loginOverlay?.addEventListener("click", (e) => {
    if (e.target === loginOverlay) closeOverlay(loginOverlay);
  });

  registerOverlay?.addEventListener("click", (e) => {
    if (e.target === registerOverlay) closeOverlay(registerOverlay);
  });

  openRegisterFromLogin?.addEventListener("click", (e) => {
    e.preventDefault();
    closeOverlay(loginOverlay);
    openOverlay(registerOverlay);
  });

  openLoginFromRegister?.addEventListener("click", (e) => {
    e.preventDefault();
    closeOverlay(registerOverlay);
    openOverlay(loginOverlay);
  });

  // Login Form Submit
  loginForm?.addEventListener("submit", function (e) {
    const username = document.getElementById("login-username").value.trim();
    const password = document.getElementById("login-password").value.trim();

    if (!username || !password) {
      e.preventDefault(); // Hanya cegah jika kosong
      alert("Silakan isi username dan password dengan benar.");
    } else {
      alert(`Login berhasil. Selamat datang, ${username}.`);
      // Setelah alert, form tetap dikirim ke login.php
    }
  });

  document.addEventListener("DOMContentLoaded", function () {
    const loginOverlay = document.getElementById("loginOverlay");

    // Cek URL apakah mengandung ?loginpopup=true
    const urlParams = new URLSearchParams(window.location.search);
    const showLogin = urlParams.get("loginpopup");

    if (showLogin === "true" && loginOverlay) {
      loginOverlay.classList.add("active");
      document.body.style.overflow = "hidden";

      // Hilangkan query dari URL biar bersih
      const cleanUrl = window.location.origin + window.location.pathname;
      window.history.replaceState({}, document.title, cleanUrl);
    }
  });
});
