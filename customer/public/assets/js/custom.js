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
        ? "linear-gradient(to right, #1f1f1f, #212121)"
        : "linear-gradient(to right, #ff416c, #ff4b2b)",
    },
    onClick: function () {},
  }).showToast();
}