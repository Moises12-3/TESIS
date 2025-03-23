<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Comercial</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- jsPDF y autoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center">Factura Electrónica</h2>
            <p><strong>Ident. Dimex:</strong> 155810709601</p>
            <p><strong>Dirección:</strong> 50Norte, 200 Este y 21 Sur del hotel del trópico</p>
            <p><strong>Teléfono:</strong> +(506) 6244-9726</p>
            <p><strong>Fax:</strong> +(506) 0</p>
            <p><strong>Correo:</strong> usedaantonio84@gmail.com</p>
            <p><strong>Factura Electrónica N°:</strong> 00100001010000000054</p>
            <p><strong>Clave Numérica:</strong> 50612022515581070960100100001010000000054103894686</p>
            <p><strong>Fecha de Emisión:</strong> 12/02/2025 9:20 a.m.</p>
            <p><strong>Condición de Venta:</strong> Contado</p>
            <p><strong>Medio de Pago:</strong> Efectivo</p>

            <h4>Receptor</h4>
            <p><strong>Receptor:</strong> ASOCIACION SOLIDARISTA DE EMPLEADOS DE STANDARD FRUIT COMPANY DE COSTA RICA S.A.</p>
            <p><strong>Ident. Jurídica:</strong> 3-002-691758</p>
            <p><strong>Teléfono:</strong> +(506) 8506-8872</p>
            <p><strong>Correo:</strong> asesf1y2fe@gmail.com</p>
            <p><strong>Código Interno:</strong> 1</p>
            <p><strong>Destinatario:</strong> Asesf G 1 y 2</p>
            <p><strong>Dirección:</strong> Comisariato Guapiles 1y 2</p>

            <h4>Detalles de Productos</h4>
            <table class="table table-bordered" id="tablaFactura">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción del Producto/Servicio</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>013199900</td>
                        <td>Ajo 1x3</td>
                        <td>12.00</td>
                        <td>Unid</td>
                        <td>₡25.00</td>
                        <td>₡300.00</td>
                    </tr>
                    <tr>
                        <td>013199900</td>
                        <td>Cebolla</td>
                        <td>6.00</td>
                        <td>kg</td>
                        <td>₡1,550.00</td>
                        <td>₡9,300.00</td>
                    </tr>
                    <tr>
                        <td>013199900</td>
                        <td>Papa</td>
                        <td>2.50</td>
                        <td>kg</td>
                        <td>₡3,325.00</td>
                        <td>₡8,312.50</td>
                    </tr>
                    <tr>
                        <td>013199900</td>
                        <td>Manzana bolsa 1x6</td>
                        <td>4.00</td>
                        <td>Unid</td>
                        <td>₡1,250.00</td>
                        <td>₡5,000.00</td>
                    </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-6">
                    <h4>Total</h4>
                    <p><strong>Subtotal Neto:</strong> ₡25,312.50</p>
                    <p><strong>Total Impuesto:</strong> ₡0.00</p>
                </div>
                <div class="col-6 text-right">
                    <h4>Total Factura</h4>
                    <p><strong>₡25,312.50</strong></p>
                    <p><strong>veinticinco mil trescientos doce COLONES con 50/100</strong></p>
                </div>
            </div>

            <p><strong>Autorizado mediante la resolución DGT-R-033-2019 del veinte de junio de dos mil diecinueve de la Dirección General de Tributación.</strong></p>
            <p><strong>Generada por GTI, www.facturaelectronica.cr</strong></p>
            <p><strong>Versión del Documento Electrónico:</strong> 4.3</p>

            <button class="btn btn-primary" id="exportarPDF">Exportar a PDF</button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.getElementById('exportarPDF').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Título de la factura
        doc.setFontSize(16);
        doc.text("Factura Electrónica", 20, 20);

        // Datos de la factura
        doc.setFontSize(12);
        doc.text("Ident. Dimex: 155810709601", 20, 30);
        doc.text("Dirección: 50Norte, 200 Este y 21 Sur del hotel del trópico", 20, 35);
        doc.text("Teléfono: +(506) 6244-9726", 20, 40);
        doc.text("Fax: +(506) 0", 20, 45);
        doc.text("Correo: usedaantonio84@gmail.com", 20, 50);
        doc.text("Factura Electrónica N°: 00100001010000000054", 20, 55);
        doc.text("Fecha de Emisión: 12/02/2025 9:20 a.m.", 20, 60);
        doc.text("Condición de Venta: Contado", 20, 65);
        doc.text("Medio de Pago: Efectivo", 20, 70);

        // Tabla de productos
        const table = document.getElementById('tablaFactura');
        const rowCount = table.rows.length;
        let tableData = [];

        for (let i = 1; i < rowCount; i++) { 
            let row = table.rows[i];
            let rowData = [];
            for (let j = 0; j < row.cells.length; j++) {
                rowData.push(row.cells[j].innerText);
            }
            tableData.push(rowData);
        }

        doc.autoTable({
            head: [['Código', 'Descripción', 'Cantidad', 'Unidad', 'Precio Unitario', 'Subtotal']],
            body: tableData,
            startY: 75
        });

        // Información total
        doc.text("Subtotal Neto: ₡25,312.50", 20, doc.lastAutoTable.finalY + 10);
        doc.text("Total Impuesto: ₡0.00", 20, doc.lastAutoTable.finalY + 15);
        doc.text("Total Factura: ₡25,312.50", 140, doc.lastAutoTable.finalY + 15);
        doc.text("Autorizado mediante la resolución DGT-R-033-2019", 20, doc.lastAutoTable.finalY + 30);

        // Descargar el PDF
        doc.save('factura_comercial.pdf');
    });
</script>

</body>
</html>
