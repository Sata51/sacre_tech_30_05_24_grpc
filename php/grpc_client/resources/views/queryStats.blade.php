<!DOCTYPE html>

<html>

<div class="chart-container" style="position: relative; height:400px; width:400px">
  <canvas id="polar" width="100" height="100"></canvas>
</div>

<div class="chart-container" style="position: relative; height:400px; width:400px">
  <canvas id="time" width="100" height="100"></canvas>
</div>

<div class="chart-container" style="position: relative; height:400px; width:600px">
  <canvas id="byTime" width="600" height="400"></canvas>
</div>

<div class="chart-container" style="position: relative; height:400px; width:600px">
  <canvas id="byTimeNodeStatic" width="600" height="400"></canvas>
</div>

<div class="chart-container" style="position: relative; height:400px; width:600px">
  <canvas id="byTimeNodeDynamic" width="600" height="400"></canvas>
</div>

<div class="chart-container" style="position: relative; height:400px; width:600px">
  <canvas id="byTimeGo" width="600" height="400"></canvas>
</div>

<div class="chart-container" style="position: relative; height:400px; width:600px">
  <canvas id="byTimeDart" width="600" height="400"></canvas>
</div>

<div class="chart-container" style="position: relative; height:400px; width:600px">
  <canvas id="byTimePython" width="600" height="400"></canvas>
</div>

</html>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
  const renderShareChart = (containerId, data) => {
    const ctx = document.getElementById(containerId).getContext('2d');

    const config = {
      type: 'polarArea',
      data: {
        labels: Object.keys(queryData).map((data) => data),
        datasets: [{
          label: 'Per language response',
          data: Object.entries(queryData).map(([key, value]) => {
            return value.length;
          }),
        }]
      },
    }
    new Chart(ctx, config);
  }

  const renderTimeChart = (containerId, data) => {
    const ctx = document.getElementById(containerId).getContext('2d');

    const max = {};
    const avg = {};
    const min = {};
    Object.entries(queryData).map(([key, value]) => {
      console.log(value.map((data) => data.duration));
      max[key] = Math.max(...value.map((data) => data.duration));
      avg[key] = value.reduce((acc, data) => acc + data.duration, 0) / value.length;
      min[key] = Math.min(...value.map((data) => data.duration));
    });

    const labels = Object.keys(queryData).map((data) => data);
    labels.sort().reverse();

    const config = {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Max response time (µs)',
          data: labels.map((label) => max[label]),
        }, {
          label: 'Avg response time (µs)',
          data: labels.map((label) => avg[label]),
        }, {
          label: 'Min response time (µs)',
          data: labels.map((label) => min[label]),
        }]
      },
    }
    new Chart(ctx, config);
  }

  const renderByTimeChart = (containerId, data) => {
    const ctx = document.getElementById(containerId).getContext('2d');

    const timeEntries = {};
    Object.entries(queryData).map(([key, value]) => {
      value.map((data) => {
        timeEntries[data.request_time] = data.duration;
      });
    });
    const labels = Object.keys(timeEntries);
    labels.sort();

    const config = {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Response time global (µs)',
          data: labels.map((label) => timeEntries[label]),
          showLine: true,
        }, ],
      },
    }
    new Chart(ctx, config);
  }

  const renderByTimeAndLangChart = (containerId, lang, data) => {
    const ctx = document.getElementById(containerId).getContext('2d');

    const timeEntries = {};
    Object.entries(queryData).map(([key, value]) => {
      if (key.toLocaleLowerCase() !== lang.toLocaleLowerCase()) {
        return;
      }
      value.map((data) => {
        timeEntries[data.request_time] = data.duration;
      });
    });
    const labels = Object.keys(timeEntries);
    labels.sort();

    const config = {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: `Response time ${lang} (µs)`,
          data: labels.map((label) => timeEntries[label]),
        }],
      },
    }
    new Chart(ctx, config);
  }


  const queryData = @json($reqRes);

  renderShareChart('polar', queryData)
  renderTimeChart('time', queryData);
  renderByTimeChart('byTime', queryData);
  renderByTimeAndLangChart('byTimeNodeStatic', 'node-static', queryData);
  renderByTimeAndLangChart('byTimeNodeDynamic', 'node-dynamic', queryData);
  renderByTimeAndLangChart('byTimeGo', 'go', queryData);
  renderByTimeAndLangChart('byTimeDart', 'dart', queryData);
  renderByTimeAndLangChart('byTimePython', 'python', queryData);
</script>