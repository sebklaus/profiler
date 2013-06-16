<table>
    <tr>
        <th>File</th>
    </tr>
    @foreach($includedFiles as $file)
		<tr>
			<td>{{ $file }}</td>
		</tr>
    @endforeach
</table>