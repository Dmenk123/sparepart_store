<script type="text/javascript">
	let save_method; //for save method string
	let table;
   let baseUrl = '<?php echo base_url(); ?>';

$(document).ready(function() {
   var ctx = document.getElementById("canvas").getContext("2d");
   var chart = new Chart(ctx, {
      type: 'line',
      data: {
          labels: <?php echo $lbl_grafik; ?>,
          datasets: [{
              label:  '<?php echo $periode_label ;?>',
              data: <?php echo $qty_grafik; ?>,
              backgroundColor:  "rgba(93, 65, 205, 0.5)",
              borderColor: "rgba(45, 7, 163, 1)",
              pointBorderColor : "rgba(45, 7, 163, 1)",
              borderWidth: 1
          }],
      },
      options: {
          legend: {
              labels: {
                  // This more specific font property overrides the global property
                  fontColor: 'black'
              }
          },  
          scales: {
              yAxes: [{
                  scaleLabel: {
                      display: true,
                      labelString: 'Nilai Omset',
                      fontColor: "black"
                  },
                  ticks: {
                     beginAtZero:true,
                     callback: function(value, index, values) {
                        if(parseInt(value) >= 1000){
                           return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        } else {
                           return 'Rp ' + value;
                        }
                     }
                  },

              }],
              xAxes: [{
                  scaleLabel: {
                      display: true,
                      labelString: 'Periode',
                      fontColor: "black"
                  },
                  ticks: {
                     beginAtZero:true
                  }
              }]
          }
      }
   });

   //generate
   document.getElementById("legenda").innerHTML = chart.generateLegend();

   var ctx2 = document.getElementById("canvas2").getContext("2d");
   var chart2 = new Chart(ctx2, {
      type: 'bar',
      data: {
          labels: <?php echo $lbl_grafik2; ?>,
          datasets: [{
              label:  '<?php echo $periode_label2 ;?>',
              data: <?php echo $qty_grafik2; ?>,
              backgroundColor:  "rgb(0, 255, 0)",
              borderColor: "rgb(0, 102, 0)",
              pointBorderColor : "rgba(45, 7, 163, 1)",
              borderWidth: 1
          }],
      },
      options: {
          legend: {
              labels: {
                  // This more specific font property overrides the global property
                  fontColor: 'black'
              }
          },  
          scales: {
              yAxes: [{
                  scaleLabel: {
                      display: true,
                      labelString: 'Qty',
                      fontColor: "black"
                  },
                  ticks: {
                     beginAtZero:true
                  },

              }],
              xAxes: [{
                  scaleLabel: {
                      display: true,
                      labelString: 'Nama Barang',
                      fontColor: "black"
                  },
                  ticks: {
                     beginAtZero:true
                  }
              }]
          }
      }
   });

   //generate
   document.getElementById("legenda2").innerHTML = chart2.generateLegend();
});

</script>	
