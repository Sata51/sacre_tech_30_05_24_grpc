<!DOCTYPE html>
<html>

<body>
  <h1>Calculation result from {{ $lang }}</h1>
  <h2>for A: {{ $a }} and B: {{ $b }}</h2>
  <ul>
    <li>Addition: {{ $addition }}</li>
    <li>Subtraction: {{ $subtraction }}</li>
    <li>Multiplication: {{ $multiplication }}</li>
    <li>Division: {{ $division }}</li>
    <br />
    <li>Power: {{ $power }}</li>
    <li>Modulus: {{ $mod }}</li>
    <br />
    <li>Square root of A: {{ $sqrtA }}</li>
    <li>Square root of B: {{ $sqrtB }}</li>
    <br />
    <li>Factorial of A: {{ $factorialA }}</li>
    <li>Factorial of B: {{ $factorialB }}</li>
  </ul>

  <br />

  <p>Elased time: {{ $elapsed }}ms</p>
</body>

</html>