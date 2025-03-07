
// import Chart from 'chart.js/auto';

window.demo = {
    

    initStudentInteractionChart: async function () {
      

      gradientBarChartConfiguration = {
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        tooltips: {
          backgroundColor: '#333333',
          titleFontColor: '#333',
          bodyFontColor: '#666',
          bodySpacing: 4,
          xPadding: 12,
          mode: "nearest",
          intersect: 0,
          position: "nearest"
        },
        responsive: true,
        scales: {
          yAxes: [{
            gridLines: {
              color: 'rgba(88, 92, 95, 0.1)',
              zeroLineColor: "rgba(88, 92, 95, 0.5)",
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 20,
              stepSize: 5,
              padding: 20,
              fontColor: "#333333",
              fontSize: 13,
              fontFamily: 'Poppins'
            }
          }],
  
          xAxes: [{
            gridLines: {
              color: 'transparent',
              zeroLineColor: "rgba(88, 92, 95, 0.5)",
            },
            ticks: {
              padding: 20,
              fontColor: "#333333",
              fontSize: 13,
              fontFamily: 'Poppins'
            }
          }]
        }
      };
  
  
      var ctx = document.getElementById("StudentInteractionChart").getContext("2d");
      
  
      async function getStudentInteractionData() {
        try {
          const response = await fetch('/student-inactivity');
          const data = await response.json(); // Assume the response is a JSON object
          // Returning the student interaction data for 7, 30, 60 days
          return [data.inactive_7_days, data.inactive_30_days, data.inactive_60_days];
        } catch (error) {
          console.error('Error fetching student interaction data:', error);
          return [0, 0, 0]; // Fallback data in case of an error
        }
      }
  

      
      
      // var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);
  
      // gradientStroke.addColorStop(1, 'rgba(29,140,248,0.2)');
      // gradientStroke.addColorStop(0.4, 'rgba(29,140,248,0.0)');
      // gradientStroke.addColorStop(0, 'rgba(29,140,248,0)'); //blue colors
      
      const data = await getStudentInteractionData();
      console.log('Fetched data:', data); 
      var myChart =  new Chart(ctx, {
          type: 'bar',
          responsive: true,
          legend: {
            display: false
          },
          data: {
            labels: ['7 days', '30 days', '60 days'],
            datasets: [{
              label: "Students",
              fill: true,
              barThickness: 100,
              maxBarThickness: 100,
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
              data: data,
            }]
          },
          options: gradientBarChartConfiguration
        });
        console.log('Chart Initialized:', myChart);


    },
   

    initTutorMessagesChart: async function () {
  
      gradientBarChartConfiguration = {
        maintainAspectRatio: false,
        legend: {
          display: false
        },
  
        tooltips: {
          backgroundColor: '#333333',
          titleFontColor: '#333',
          bodyFontColor: '#666',
          bodySpacing: 4,
          xPadding: 12,
          mode: "nearest",
          intersect: 0,
          position: "nearest"
        },
        responsive: true,
        scales: {
          yAxes: [{
  
            gridLines: {
              color: 'rgba(88, 92, 95, 0.1)',
              zeroLineColor: "rgba(88, 92, 95, 0.5)",
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 50,
              stepSize: 10,
              padding: 20,
              fontColor: "#333333",
              fontSize: 13,
              fontFamily: 'Poppins'
            }
          }],
  
          xAxes: [{
            gridLines: {
              color: 'transparent',
              zeroLineColor: "rgba(88, 92, 95, 0.5)",
            },
            ticks: {
              padding: 20,
              fontColor: "#333333",
              fontSize: 13,
              fontFamily: 'Poppins'
            }
          }]
        }
      };
  
  
      var ctx = document.getElementById("TutorMessagesChart").getContext("2d");
      async function getAverageMessagesPerTutor() {
        try {
          const response = await fetch('/average_messages');
          const data = await response.json(); // API response with "labels" and "data"
          
          // Extracting labels and message count data
          const labels = data.labels;
          const messageCounts = data.data;
          var now = new Date();
          
          var day = new Date(now.getFullYear(), now.getMonth()+1, 0).getDate();
          // Calculate average message count per tutor
          const averageMessageCounts = messageCounts.map(count => {
              // If count is greater than 0, calculate the average
              return count > 0 ? (count / day).toFixed(2):0;
          });
          
          // Returning both labels and average message counts in a single object
          return { labels, averageMessageCounts};
      } catch (error) {
          console.error('Error fetching average messages:', error);
  
          // Fallback in case of an error
          return {
              labels: [],
              averageMessageCounts: []
          };
      }
      }
    
      const { labels, averageMessageCounts } = await getAverageMessagesPerTutor();

      
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
            backgroundColor: '#004AAD',
            hoverBackgroundColor: '#004AAD',
            borderColor: '#004AAD',
            borderWidth: 2,
            borderDash: [],
            borderDashOffset: 0.0,
            data: averageMessageCounts,
          }]
        },
        options: gradientBarChartConfiguration
      });
    }
  }