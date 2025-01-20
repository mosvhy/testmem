<style>
    table, thead, tbody, tr, th ,td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 0.4rem;
        font-family: monospace;
    }
</style>
<table>
    <thead id="head"></thead>
    <tbody id="data"></tbody>
</table>
<script>
fetch('/api/test').then(res=>res.json()).then(data=>{
    console.log('start')
    let trHead = '<tr>'
    data.meta_data.forEach(col => {
        trHead += `<th>${col}</th>`
    });
    document.getElementById('head').innerHTML = trHead;

    let index = 0;
    function renderRow() {
        if (index < data.merged_data.length) {
            const row = data.merged_data[index];
            // document.getElementById('data').innerHTML += `<tr>${row.map(cell => `<td>${cell}</td>`).join('')}</tr>`;
            let cells = '';
            row.forEach((cell, cellIndex) => {
                const colName = data.meta_data[cellIndex];
                const t1Value = data.merged_data[index][cellIndex];
                const t2Value = data.merged_data[index + 1] ? data.merged_data[index + 1][cellIndex] : t1Value;
                const t3Value = data.merged_data[index + 2] ? data.merged_data[index + 2][cellIndex] : t1Value;

                let bgColor = '';
                if (t1Value !== t2Value || t1Value !== t3Value || t2Value !== t3Value) {
                    bgColor = 'background-color: yellow;';
                }

                cells += `<td style="${bgColor}">${cell}</td>`;
            });
            document.getElementById('data').innerHTML += `<tr>${cells}</tr>`;
            index++;
            requestAnimationFrame(renderRow);
        }
    }
    requestAnimationFrame(renderRow);
    console.log('done')
})
</script>