(function($) {
  'use strict';
  $(function() {



    if (document.getElementById("income-chart")) {
      const ctx = document.getElementById("income-chart").getContext("2d");
      new Chart(ctx, {
        type: "line",
        data: {
          labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
          datasets: [
            {
              data: [80, 180, 80, 200, 140, 180, 70],
              borderColor: "#a43cda",
              borderWidth: 2,
              fill: true,
              backgroundColor: "rgba(164, 60, 218, .1)",
              pointRadius: 0,
            },
            {
              data: [200, 340, 200, 340, 220, 310, 190],
              borderColor: "#00c8bf",
              borderWidth: 2,
              fill: true,
              backgroundColor: "rgba(0, 200, 191, .1)",
              pointRadius: 0,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          elements: {
            line: {
              tension: 0.4,
            },
          },
          scales: {
            x: {
              grid: {
                display: false,
              },
              ticks: {
                color: "#001737",
                font: {
                  size: 11,
                  weight: 300,
                },
              },
            },
            y: {
              beginAtZero: true,
              grid: {
                display: true,
              },
              ticks: {
                color: "#001737",
                font: {
                  size: 11,
                  weight: 300,
                },
              },
            },
          },
          plugins: {
            legend: {
              display: false,
            },
          },
        },
      });
    }
    
    
  });
})(jQuery);



// Dark Mode Toggle JavaScript
document.addEventListener("DOMContentLoaded", function () {
  const darkModeToggle = document.getElementById("darkModeToggle");
  const body = document.body;

  // Check for dark mode preference in cookies
  if (document.cookie.includes("theme=dark")) {
      body.classList.add("dark-mode");
      darkModeToggle.checked = true;
  }

  darkModeToggle.addEventListener("change", function () {
      if (this.checked) {
          body.classList.add("dark-mode");
          document.cookie = "theme=dark; path=/; max-age=" + 60 * 60 * 24 * 30; // 30 days
      } else {
          body.classList.remove("dark-mode");
          document.cookie = "theme=light; path=/; max-age=" + 60 * 60 * 24 * 30;
      }
  });
});
