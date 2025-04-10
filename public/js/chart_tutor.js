
var studentInteractionChart; // Declare a global variable to store the chart instance

window.demo = {

  initStudentInteractionsChart: async function (studentNames,interactionCounts) {
    if (studentInteractionChart) {
      studentInteractionChart.destroy();
    }
    
    console.log("init respone" + studentNames + interactionCounts);
    
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
        }
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
    
    // async function getInteractionCounts() {
    //   try {

    //     const response = await fetch('/tutor_student_interaction_dashboard');
    //     const data = await response.json(); 
      
    //     // Extract student names and interaction counts
    //     const studentNames = data.map(item => item.student.first_name + " " + item.student.last_name);
    //     const interactionCounts = data.map(item => item.interactions);
    //     console.log("method called try")
    //     return {studentNames,interactionCounts};
        
    //   } catch (error) {
    //     console.error('Error fetching student interaction data:', error);
    //     return {studentNames: [], interactionCounts: []};
        
    //   }
    // }
    // const { studentNames, interactionCounts } = await getInteractionCounts();
    
    
      var ctx = document.getElementById("StudentInteractionCountChart").getContext("2d");
      if(studentInteractionChart){
        studentInteractionChart.destroy();
      }
      studentInteractionChart = new Chart(ctx, {
        type: 'bar',
        responsive: true,
        legend: {
          display: false
        },
        data: {
          labels: studentNames,
          datasets: [{
            label: "Count",
            fill: true,
            backgroundColor: '#004AAD',
            hoverBackgroundColor: '#004AAD',
            borderColor: '#004AAD',
            borderWidth: 1,
            borderDash: [],
            borderDashOffset: 0.0,
            data: interactionCounts,
          }]
        },
        options: gradientBarChartConfigurationTutor,
      });
      console.log(studentInteractionChart);
      console.log(interactionCounts);
   
  }
}