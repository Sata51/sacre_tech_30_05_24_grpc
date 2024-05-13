<x-layout>

  <div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
      <div class="max-w-md">
        <h1 class="text-5xl font-bold mb-10">
          Répartition des réponses par langage
        </h1>
        <div class="relative max-w-md content-center" style="height:400px; width:400px">
          <canvas id="polar" width="100" height="100"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="divider"></div>

  <div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
      <div class="max-w-md">
        <h1 class="text-5xl font-bold mb-10">
          Temps de réponses par langage
        </h1>
        <div class="relative max-w-md content-center" style="height:400px; width:400px">
          <canvas id="time" width="100" height="100"></canvas>
        </div>
      </div>
    </div>
  </div>


  <div class="divider"></div>

  <div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
      <div class="max-w-md">
        <h1 class="text-5xl font-bold mb-10">
          Évolution du temps de réponses sur la séquence de test
        </h1>
        <div class="relative max-w-md content-center" style="height:400px; width:400px">
          <canvas id="byTime" width="600" height="400"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="divider"></div>
  <div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
      <div class="max-w-fit">
        <h1 class="text-5xl font-bold mb-10">
          Évolution du temps de réponses par langage
        </h1>
        <div class="grid gap-4 grid-cols-2 grid-rows-3">
          <div class="relative max-w-md content-center" style="height:400px; width:400px">
            <canvas id="byTimeNodeStatic" width="600" height="400"></canvas>
            <h1 class="text-3xl font-bold mt-10">Node static</h1>
          </div>
          <div class="relative max-w-md content-center" style="height:400px; width:400px">
            <canvas id="byTimeNodeDynamic" width="600" height="400"></canvas>
            <h1 class="text-3xl font-bold mt-10">Node dynamic</h1>
          </div>
          <div class="relative max-w-md content-center" style="height:400px; width:400px">
            <canvas id="byTimeGo" width="600" height="400"></canvas>
            <h1 class="text-3xl font-bold mt-10">Go</h1>
          </div>
          <div class="relative max-w-md content-center" style="height:400px; width:400px">
            <canvas id="byTimeDart" width="600" height="400"></canvas>
            <h1 class="text-3xl font-bold mt-10">Dart</h1>
          </div>
          <div class="relative max-w-md content-center" style="height:400px; width:400px">
            <canvas id="byTimePython" width="600" height="400"></canvas>
            <h1 class="text-3xl font-bold mt-10">Python</h1>
          </div>
          <div class="relative max-w-md content-center" style="height:400px; width:400px">
            <canvas id="byTimeRuby" width="600" height="400"></canvas>
            <h1 class="text-3xl font-bold mt-10">Ruby</h1>
          </div>
          <div class="relative max-w-md content-center" style="height:400px; width:400px">
            <canvas id="byTimeRust" width="600" height="400"></canvas>
            <h1 class="text-3xl font-bold mt-10">Rust</h1>
          </div>
        </div>
      </div>
    </div>
  </div>




</x-layout>



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
  renderByTimeAndLangChart('byTimeRuby', 'ruby', queryData);
  renderByTimeAndLangChart('byTimeRust', 'rust', queryData);
</script>