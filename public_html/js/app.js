// サイドメニュー表示切替関数
const toggle_menu = () => {
  const el_toggle_menu = document.getElementById("toggle_menu");
  const side_menu = document.getElementById("side_menu");

  side_menu.style.width = side_menu.style.width === "200px" ? "80px" : "200px";
  el_toggle_menu.classList.toggle("fa-angle-right");
  el_toggle_menu.classList.toggle("fa-angle-left");
  document.getElementById("site_name").classList.toggle("d-none");
  document
    .querySelectorAll("#side_menu_list li span")
    ?.forEach((el) => el.classList.toggle("d-none"));
};

// 初期処理
window.onload = () => {
  // サイドメニュー切り替えアイコン押下時イベント登録
  document.getElementById("toggle_menu").addEventListener("click", toggle_menu);

  // 画面幅に応じてサイドメニュー縮小
  if (screen.width < 768) {
    toggle_menu();
  }
};
