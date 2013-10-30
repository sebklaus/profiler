<table>
	<tr>
		<th>Key</th>
		<th>Value</th>
	</tr>
	<tr>
		<td>Current route</td>
		<td>{{ Route::current()->getName() }}</td>
	</tr>
	<tr>
		<td>Current controller action</td>
		<td>{{ Route::current()->getActionName() }}</td>
	</tr>
</table>
