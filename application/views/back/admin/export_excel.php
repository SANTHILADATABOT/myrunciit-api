<div id="content-container">
	<div id="page-title">

		<h1 class="page-header text-overflow" style="display:none;"><?php echo translate('Product Export'); ?>

	</div>

	<div class="tab-base">

		<div class="panel">

			<div class="panel-body">

				<div class="tab-content">
					<!-- LIST -->

					<div class="tab-pane fade active in" id="list" style="border:1px solid #ebebeb; border-radius:4px;">


						<div id='export-div' style="padding:40px;; text-align:center;">

							<h1 style="display:none;"><?php echo translate('products'); ?></h1>

							<table id="export-table" class="table" data-name='products' data-orientation='p' data-width='1500' style="display:none;">
							
								<thead>

									<tr>
										<th>No</th>
										<th style="width:75px">Image</th>
										<th>Title</th>
										<th>Current_quantity</th>
										<th>today's_deal</th>
										<th>publish</th>

									</tr>

								</thead>

								<tbody>

									<?php

									$i = 0;

									foreach ($excels as $row) {
										//   print_r($row); exit;

										$i++;

									?>

										<tr>

											<td><?php echo $i; ?></td>
											<td style="width:75px; position:center">
												<div>
												<img src="<?php echo $this->crud_model->file_view('product', $row['product_id'], '', '', 'thumb', 'src', 'multi', 'one'); ?>" width="50" height="50" alt="">
												</div>
											</td>
											<td><?php echo str_replace('#', '', $row['title']); ?></td>
											<td><?php echo $row['current_stock']; ?></td>
											<td><?php if ($row['deal'] == 'ok') {
													echo 'yes';
												} else {
													echo 'no';
												} ?></td>
											<td><?php if ($row['status'] == 'ok') {
													echo 'yes';
												} else {
													echo 'no';
												} ?></td>
										</tr>

									<?php } ?>
								</tbody>
							</table>
						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>
<script src="<?php echo base_url(); ?>template/back/js/jquery-3.6.0.min.js"></script>
	<script src="<?php echo base_url(); ?>template/back/js/jquery.tableToExcel.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		// $('#export-table').show();
		export_it('excel');
		// Simulate a mouse click:
		setTimeout(() => {
			window.location.href = "<?php echo base_url(); ?>admin/product_stock/";
		}, 2000);
	});

	function export_it(type, ignore) {
		//alert(1);
		$('#export-table').show();
		var name = $('#export-table').data('name');
		var o = $('#export-table').data('orientation');
		var w = $('#export-table').data('width');
		if (type == 'pdf') {
			//FromaHTML('export-div',name,o);
			FromHTML('export-div', name, o, w);
		} else {
			//alert(2);
			$('#export-table').tblToExcel();
			// $('#export-table').tableExport({
			// 	type: type,
			// 	tableName: name,
			// 	htmlContent: true,
			// });
			$('#export-table').hide();
		}
	}

	(function($) {
		$.fn.extend({
			tableExport: function(options) {
				var defaults = {
					separator: ',',
					ignoreColumn: [],
					tableName: 'yourTableName',
					type: 'csv',
					pdfFontSize: 14,
					pdfLeftMargin: 20,
					escape: 'true',
					htmlContent: 'true',
					consoleLog: 'false'
				};

				var options = $.extend(defaults, options);
				var el = this;

				if (defaults.type == 'csv' || defaults.type == 'txt') {

					// Header
					var tdData = "";
					$(el).find('thead').find('tr').each(function() {
						tdData += "\n";
						$(this).filter(':visible').find('th').each(function(index, data) {
							if ($(this).css('display') != 'none') {
								if (defaults.ignoreColumn.indexOf(index) == -1) {
									tdData += '"' + parseString($(this)) + '"' + defaults.separator;
								}
							}

						});
						tdData = $.trim(tdData);
						tdData = $.trim(tdData).substring(0, tdData.length - 1);
					});

					// Row vs Column
					$(el).find('tbody').find('tr').each(function() {
						tdData += "\n";
						$(this).filter(':visible').find('td').each(function(index, data) {
							if ($(this).css('display') != 'none') {
								if (defaults.ignoreColumn.indexOf(index) == -1) {
									tdData += '"' + parseString($(this)) + '"' + defaults.separator;
								}
							}
						});
						//tdData = $.trim(tdData);
						tdData = $.trim(tdData).substring(0, tdData.length - 1);
					});

					//output
					if (defaults.consoleLog == 'true') {
						console.log(tdData);
					}
					//var base64data = "base64," + $.base64.encode(tdData);
					//window.open('data:application/'+defaults.type+';filename=exportData;' + base64data);
					var a = document.createElement('a');
					//a.href        = 'data:application/'+defaults.type+';filename=exportData;' + base64data;
					var encodedUri = encodeURI(tdData);
					a.setAttribute("href", "data:application/vnd.ms-excel;filename=exportData;charset=utf-8,\uFEFF" + encodedUri);

					a.target = '_blank';
					a.download = defaults.tableName + '.csv';
					document.body.appendChild(a);
					a.click();
					a.remove();
				} else if (defaults.type == 'sql') {

					// Header
					var tdData = "INSERT INTO `" + defaults.tableName + "` (";
					$(el).find('thead').find('tr').each(function() {

						$(this).filter(':visible').find('th').each(function(index, data) {
							if ($(this).css('display') != 'none') {
								if (defaults.ignoreColumn.indexOf(index) == -1) {
									tdData += '`' + parseString($(this)) + '`,';
								}
							}

						});
						tdData = $.trim(tdData);
						tdData = $.trim(tdData).substring(0, tdData.length - 1);
					});
					tdData += ") VALUES ";
					// Row vs Column
					$(el).find('tbody').find('tr').each(function() {
						tdData += "(";
						$(this).filter(':visible').find('td').each(function(index, data) {
							if ($(this).css('display') != 'none') {
								if (defaults.ignoreColumn.indexOf(index) == -1) {
									tdData += '"' + parseString($(this)) + '",';
								}
							}
						});

						tdData = $.trim(tdData).substring(0, tdData.length - 1);
						tdData += "),";
					});
					tdData = $.trim(tdData).substring(0, tdData.length - 1);
					tdData += ";";

					//output
					//console.log(tdData);

					if (defaults.consoleLog == 'true') {
						console.log(tdData);
					}

					var base64data = "base64," + $.base64.encode(tdData);
					window.open('data:application/sql;filename=exportData;' + base64data);


				} else if (defaults.type == 'json') {

					var jsonHeaderArray = [];
					$(el).find('thead').find('tr').each(function() {
						var tdData = "";
						var jsonArrayTd = [];

						$(this).filter(':visible').find('th').each(function(index, data) {
							if ($(this).css('display') != 'none') {
								if (defaults.ignoreColumn.indexOf(index) == -1) {
									jsonArrayTd.push(parseString($(this)));
								}
							}
						});
						jsonHeaderArray.push(jsonArrayTd);

					});

					var jsonArray = [];
					$(el).find('tbody').find('tr').each(function() {
						var tdData = "";
						var jsonArrayTd = [];

						$(this).filter(':visible').find('td').each(function(index, data) {
							if ($(this).css('display') != 'none') {
								if (defaults.ignoreColumn.indexOf(index) == -1) {
									jsonArrayTd.push(parseString($(this)));
								}
							}
						});
						jsonArray.push(jsonArrayTd);

					});

					var jsonExportArray = [];
					jsonExportArray.push({
						header: jsonHeaderArray,
						data: jsonArray
					});

					//Return as JSON
					//console.log(JSON.stringify(jsonExportArray));

					//Return as Array
					//console.log(jsonExportArray);
					if (defaults.consoleLog == 'true') {
						console.log(JSON.stringify(jsonExportArray));
					}
					var base64data = "base64," + $.base64.encode(JSON.stringify(jsonExportArray));
					window.open('data:application/json;filename=exportData;' + base64data);
				} else if (defaults.type == 'excel' || defaults.type == 'doc' || defaults.type == 'powerpoint') {
					//console.log($(this).html());
					var excel = "<table>";
					// Header
					$(el).find('thead').find('tr').each(function() {
						excel += "<tr>";
						$(this).filter(':visible').find('th').each(function(index, data) {
							if ($(this).css('display') != 'none') {
								if (defaults.ignoreColumn.indexOf(index) == -1) {
									excel += "<td>" + parseString($(this)) + "</td>";
								}
							}
						});
						excel += '</tr>';

					});


					// Row Vs Column
					var rowCount = 1;
					$(el).find('tbody').find('tr').each(function() {
						excel += "<tr>";
						var colCount = 0;
						$(this).filter(':visible').find('td').each(function(index, data) {
							if ($(this).css('display') != 'none') {
								if (defaults.ignoreColumn.indexOf(index) == -1) {
									excel += "<td>" + parseString($(this)) + "</td>";
								}
							}
							colCount++;
						});
						rowCount++;
						excel += '</tr>';
					});
					excel += '</table>'

					if (defaults.consoleLog == 'true') {
						console.log(excel);
					}

					var excelFile = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:" + defaults.type + "' xmlns='http://www.w3.org/TR/REC-html40'>";
					excelFile += "<head>";
					excelFile += "<!--[if gte mso 9]>";
					excelFile += "<xml>";
					excelFile += "<x:ExcelWorkbook>";
					excelFile += "<x:ExcelWorksheets>";
					excelFile += "<x:ExcelWorksheet>";
					excelFile += "<x:Name>";
					excelFile += "{worksheet}";
					excelFile += "</x:Name>";
					excelFile += "<x:WorksheetOptions>";
					excelFile += "<x:DisplayGridlines/>";
					excelFile += "</x:WorksheetOptions>";
					excelFile += "</x:ExcelWorksheet>";
					excelFile += "</x:ExcelWorksheets>";
					excelFile += "</x:ExcelWorkbook>";
					excelFile += "</xml>";
					excelFile += "<![endif]-->";
					excelFile += "</head>";
					excelFile += "<body>";
					excelFile += excel;
					excelFile += "</body>";
					excelFile += "</html>";

					if (defaults.type == 'excel') {
						var filename = defaults.tableName + '.xls';
					}
					if (defaults.type == 'doc') {
						var filename = 'myFile.docx';
					}
					if (defaults.type == 'powerpoint') {
						var filename = 'myFile.ppt';
					}

					//var base64data = "base64," + $.base64.encode(excelFile);
					var a = document.createElement('a');
					var encodedUri = encodeURI(excelFile);
					a.href = 'data:application/vnd.ms-' + defaults.type + ';filename=exportData;charset=utf-8,\uFEFF' + encodedUri;
					//a.setAttribute("href", "data:text/csv;charset=utf-8,\uFEFF" + base64data);

					a.target = '_blank';
					a.download = filename;
					document.body.appendChild(a);
					a.click();
					a.remove();
					//window.open('data:application/vnd.ms-'+defaults.type+';filename=exportData.doc;' + base64data);

				} else if (defaults.type == 'png') {
					html2canvas($(el), {
						onrendered: function(canvas) {
							var img = canvas.toDataURL("image/png");
							window.open(img);


						}
					});
				} else if (defaults.type == 'pdf') {

					var doc = new jsPDF('p', 'pt', 'a3', true);
					doc.setFontSize(defaults.pdfFontSize);

					// Header
					var startColPosition = defaults.pdfLeftMargin;
					$(el).find('thead').find('tr').each(function() {
						$(this).filter(':visible').find('th').each(function(index, data) {
							if ($(this).css('display') != 'none') {
								if (defaults.ignoreColumn.indexOf(index) == -1) {
									var colPosition = startColPosition + (index * 50);
									doc.text(colPosition, 20, parseString($(this)));
								}
							}
						});
					});


					// Row Vs Column
					var startRowPosition = 20;
					var page = 1;
					var rowPosition = 0;
					$(el).find('tbody').find('tr').each(function(index, data) {
						rowCalc = index + 1;

						if (rowCalc % 26 == 0) {
							doc.addPage();
							page++;
							startRowPosition = startRowPosition + 10;
						}
						rowPosition = (startRowPosition + (rowCalc * 10)) - ((page - 1) * 280);

						$(this).filter(':visible').find('td').each(function(index, data) {
							if ($(this).css('display') != 'none') {
								if (defaults.ignoreColumn.indexOf(index) == -1) {
									var colPosition = startColPosition + (index * 50);
									doc.text(colPosition, rowPosition, parseString($(this)));
								}
							}

						});

					});

					// Output as Data URI
					doc.output('save', '', defaults.tableName);

				}


				function parseString(data) {

					if (defaults.htmlContent == 'true') {
						content_data = data.html().trim();
					} else {
						content_data = data.text().trim();
					}

					if (defaults.escape == 'true') {
						content_data = escape(content_data);
					}



					return content_data;
				}

			}
		});
	})(jQuery);
</script>