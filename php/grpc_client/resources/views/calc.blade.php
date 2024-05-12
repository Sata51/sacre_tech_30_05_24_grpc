<x-layout>
  <div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
      <div class="max-w-fit">

        <h1 class="text-5xl font-bold mb-10">Calculation result from {{ $lang }}</h1>
        <h3 class="text-3xl font-bold mb-10">for A: {{ $a }} and B: {{ $b }}</h3>

        <div class="flex justify-center">
          <table class="table-auto border-collapse">
            <thead>
              <tr>
                <th> Opération </th>
                <th> Résultat </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th> Addition </th>
                <th> {{ $addition }} </th>
              </tr>
              <tr>
                <th> Soustraction </th>
                <th> {{ $subtraction }} </th>
              </tr>
              <tr>
                <th> Multiplication </th>
                <th> {{ $multiplication }} </th>
              </tr>
              <tr>
                <th> Division </th>
                <th> {{ $division }} </th>
              </tr>
              <tr>
                <th> Puissance </th>
                <th> {{ $power }} </th>
              </tr>
              <tr>
                <th> Modulo </th>
                <th> {{ $mod }} </th>
              </tr>
              <tr>
                <th> Racine carrée de A </th>
                <th> {{ $sqrtA }} </th>
              </tr>
              <tr>
                <th> Racine carrée de B </th>
                <th> {{ $sqrtB }} </th>
              </tr>
              <tr>
                <th> Factorielle de A </th>
                <th> {{ $factorialA }} </th>
              </tr>
              <tr>
                <th> Factorielle de B </th>
                <th> {{ $factorialB }} </th>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="stat mt-10">
          <div class="stat-title">Elased time</div>
          <div class="stat-value">{{ $elapsed }}ms</div>
        </div>
      </div>
    </div>
  </div>
</x-layout>