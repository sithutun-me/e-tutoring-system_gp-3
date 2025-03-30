

window.demo = {

  initStudentActivityChart: async function () {

    gradientBarChartConfigurationStudent = {

      barPercentage: 0.8,
      categoryPercentage: 0.6,
      elements: {
        bar: {
          maxBarThickness: 50
        }
      },
      plugins: {
        legend: {
          display: false
        },
        datalabels: {
          display: false
        },
      },
      maintainAspectRatio: false,

      tooltips: {
        backgroundColor: '#333333',
        titleFontColor: 'white',
        bodyFontColor: 'white',
        bodySpacing: 4,
        xPadding: 12,
        mode: "nearest",
        intersect: 0,
        position: "nearest",
        fontSize: 13,
        fontFamily: 'Poppins'
      },
      responsive: true,
      scales: {
        y: {
          suggestedMin: 0,
          suggestedMax: 50,
          gridLines: {
            color: 'rgba(88, 92, 95, 0.1)',
            zeroLineColor: "rgba(88, 92, 95, 0.5)",
          },
          ticks: {
            stepSize: 10,
            color: "#333333",
            font: {
              size: 13,
              family: 'Poppins'
            }
          }
        },

        x: {
          grid: {
            color: "transparent",
            drawBorder: false
          },
          gridLines: {
            color: 'transparent',
            zeroLineColor: "rgba(88, 92, 95, 0.5)",
          },
          ticks: {
            padding: 20,
            color: "#333333",
            autoSkip: false,
            font: {
              size: 13,
              family: 'Poppins'
            },
            callback: function (value, index) {
              const label = this.chart.data.labels[index]
              return label.split(" ")[0];  // Only the first word
            }
          }
        }
      }
    };
    async function getStudentActivities() {
      try {
        const response = await fetch('/myActivities');
        const chartData = await response.json();
        const labels = chartData.labels;
        const counts = chartData.data;
        return { labels, counts };
      } catch (error) {
        return {
          labels: [],
          counts: []
        };
      }
    }
    const { labels, counts } = await getStudentActivities();
    var ctx = document.getElementById("StudentActivityChart").getContext("2d");

    var myChart = new Chart(ctx, {
      type: 'bar',
      responsive: true,
      legend: {
        display: false
      },
      data: {
        labels: labels,
        datasets: [{
          label: "Messages",
          fill: true,
          barThickness: 50,
          maxBarThickness: 50,
          backgroundColor: [
            'rgba(0, 74, 173, 1)',  // Color for the first bar
            'rgba(0, 74, 173, 0.8)',  // Color for the second bar
            'rgba(0, 74, 173, 0.5)',  // Color for the third bar
          ],
          hoverBackgroundColor: [
            'rgba(0, 74, 173, 1)',  // Color for the first bar
            'rgba(0, 74, 173, 0.8)',  // Color for the second bar
            'rgba(0, 74, 173, 0.5)',  // Color for the third bar
          ],
          borderColor: [
            'rgba(0, 74, 173, 1)',  // Color for the first bar
            'rgba(0, 74, 173, 0.8)',  // Color for the second bar
            'rgba(0, 74, 173, 0.5)',  // Color for the third bar
          ],
          borderWidth: 1,
          borderDash: [],
          borderDashOffset: 0.0,
          data: counts,
        }]
      },
      options: gradientBarChartConfigurationStudent,
    });
  },
  initTutorActivityChart: async function () {

    gradientBarChartConfigurationTutor = {

      barPercentage: 0.8,
      categoryPercentage: 0.6,
      elements: {
        bar: {
          maxBarThickness: 50
        }
      },
      plugins: {
        legend: {
          display: false
        },
        datalabels: {
          display: false
        },
      },
      maintainAspectRatio: false,

      tooltips: {
        backgroundColor: '#333333',
        titleFontColor: 'white',
        bodyFontColor: 'white',
        bodySpacing: 4,
        xPadding: 12,
        mode: "nearest",
        intersect: 0,
        position: "nearest",
        fontSize: 13,
        fontFamily: 'Poppins'
      },
      responsive: true,
      scales: {
        y: {
          suggestedMin: 0,
          suggestedMax: 50,
          gridLines: {
            color: 'rgba(88, 92, 95, 0.1)',
            zeroLineColor: "rgba(88, 92, 95, 0.5)",
          },
          ticks: {
            stepSize: 10,
            color: "#333333",
            font: {
              size: 13,
              family: 'Poppins'
            }
          }
        },

        x: {
          grid: {
            color: "transparent",
            drawBorder: false
          },
          gridLines: {
            color: 'transparent',
            zeroLineColor: "rgba(88, 92, 95, 0.5)",
          },
          ticks: {
            padding: 20,
            color: "#333333",
            autoSkip: false,
            font: {
              size: 13,
              family: 'Poppins'
            },
            callback: function (value, index) {
              const label = this.chart.data.labels[index]
              return label.split(" ")[0];  // Only the first word
            }
          }
        }
      }
    };

    async function getTutorActivities() {
      try {
        const response = await fetch('/tutorActivities');
        const chartData = await response.json();
        const labels = chartData.labels;
        const counts = chartData.data;
        return { labels, counts };
      } catch (error) {
        return {
          labels: [],
          counts: []
        };
      }
    }
    const { labels, counts } = await getTutorActivities();
    var ctx = document.getElementById("TutorActivityChart").getContext("2d");

    var myChart = new Chart(ctx, {
      type: 'bar',
      responsive: true,
      legend: {
        display: false
      },
      data: {
        labels: labels,
        datasets: [{
          label: "Messages",
          fill: true,
          barThickness: 50,
          maxBarThickness: 50,
          backgroundColor: [
            'rgba(0, 74, 173, 1)',  // Color for the first bar
            'rgba(0, 74, 173, 0.8)',  // Color for the second bar
            'rgba(0, 74, 173, 0.5)',  // Color for the third bar
          ],
          hoverBackgroundColor: [
            'rgba(0, 74, 173, 1)',  // Color for the first bar
            'rgba(0, 74, 173, 0.8)',  // Color for the second bar
            'rgba(0, 74, 173, 0.5)',  // Color for the third bar
          ],
          borderColor: [
            'rgba(0, 74, 173, 1)',  // Color for the first bar
            'rgba(0, 74, 173, 0.8)',  // Color for the second bar
            'rgba(0, 74, 173, 0.5)',  // Color for the third bar
          ],
          borderWidth: 1,
          borderDash: [],
          borderDashOffset: 0.0,
          data: counts,
        }]
      },
      options: gradientBarChartConfigurationTutor,
    });
  },
  initMeetingCountChart: async function () {

    pieChartConfiguration = {
      responsive: true,
      maintainAspectRatio: false,
      layout: {
        padding: 45
      },
      plugins: {
        datalabels: {
          display: true,
          color: '#fff',
          font: {
            size: 13,
            family: 'Poppins'
          },
          formatter: (value) => value,
          anchor: 'center',
          align: 'center',
        },
        legend: {
          position: 'right',
          labels: {
            usePointStyle: true,
            pointStyle: 'circle',
            color: '#333333',
            font: {
              size: 13,
              family: 'Poppins'
            },
            generateLabels: function (chart) {
              return labels.map((label, index) => ({
                  text: label, // Show all labels in legend
                  fillStyle: labelColors[index % labelColors.length], // Use correct colors for all labels
                  hidden: false
              }));
          }
          }
        }
      }
    };

    async function getMeetingStatusCounts() {
      try {
        const response = await fetch('/meeting_counts');
        const chartMeetingData = await response.json();
        const labels = chartMeetingData.labels;
        const statusCounts = chartMeetingData.data;

        const filteredData = labels.map((label, index) => ({
          label,
          count: statusCounts[index]
        })).filter(item => item.count > 0);

        // Extract the filtered labels and counts
        const filteredCounts = filteredData.map(item => item.count);
        console.log(filteredData);
        console.log('count '+filteredCounts);

        return { labels, filteredCounts };
      } catch (error) {
        return {
          labels: [],
          statusCounts: []
        };
      }
    }
    const { labels, filteredCounts } = await getMeetingStatusCounts();
    const labelColors = ["#00B312", "#004AAD", "#D73030",'#B4B4B4'];
    var ctx = document.getElementById("MeetingCountChart").getContext("2d");

    var myChart = new Chart(document.getElementById("MeetingCountChart"), {
      type: 'pie',
      data: {
        labels: labels,
        datasets: [{
          backgroundColor: labelColors,
          data: filteredCounts
        }]
      },
      options: pieChartConfiguration
    });
  }
}