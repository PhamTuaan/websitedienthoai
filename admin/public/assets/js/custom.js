function showToast(message, isSuccess) {
  Toastify({
    text: message,
    duration: 5000,
    newWindow: true,
    close: true,
    gravity: "top",
    position: "right",
    stopOnFocus: true,
    style: {
      background: isSuccess
        ? "linear-gradient(to right, #00b09b, #96c93d)"
        : "linear-gradient(to right, #ff416c, #ff4b2b)",
    },
    onClick: function () {},
  }).showToast();
}

