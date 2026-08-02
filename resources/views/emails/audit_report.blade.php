<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Energy Audit Report</title>
</head>
<body style="font-family:Arial,sans-serif">

<h2>Energy Audit Report</h2>

<p>Hello,</p>

<p>Your energy audit has been generated successfully.</p>

<h3>Building Details</h3>

<ul>
    <li><strong>Name:</strong> {{ $building->name }}</li>
    <li><strong>Type:</strong> {{ $building->building_type }}</li>
    <li><strong>Address:</strong> {{ $building->address }}</li>
</ul>

<h3>Audit Summary</h3>

<ul>
    <li><strong>Total Daily kWh:</strong> {{ $report->total_daily_kwh }} kWh</li>
    <li><strong>Total Monthly kWh:</strong> {{ $report->total_monthly_kwh }} kWh</li>
    <li><strong>Estimated Monthly Cost:</strong> ₹{{ $report->estimated_monthly_cost }}</li>
    <li><strong>Energy Use Intensity:</strong> {{ $report->energy_use_intensity }}</li>
</ul>

<p>Thank you for using the Energy Audit Portal.</p>

</body>
</html>