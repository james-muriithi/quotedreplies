var currentPageId="page-home",currentSelectorId="home";function getButtons(){return["home","feed","create","account"]}function changePage(){var e=document.getElementById(currentSelectorId),t=document.getElementById(currentPageId),n="page-"+this.id,c=document.getElementById(n),i=document.getElementById(this.id);c.classList.contains("active")||(e.classList.remove("button-active"),e.classList.add("button-inactive"),t.classList.remove("active"),t.classList.add("inactive"),i.classList.remove("button-inactive"),i.classList.add("button-active"),c.classList.remove("inactive"),c.classList.add("active"),window.scrollTo(0,0),currentSelectorId=this.id,currentPageId=n)}"serviceWorker"in navigator&&window.addEventListener("load",function(){navigator.serviceWorker.register("service-worker.js").then(function(){console.log("Service Worker Registered,")})}),window.onload=function(){getButtons().forEach(function(e){document.getElementById(e).addEventListener("click",changePage,!1)})};
//# sourceMappingURL=main.js.map
