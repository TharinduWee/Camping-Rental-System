<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Suppliers</h1>
	</div>
	<div class="content-header-right">
		<a href="supplier-add.php" class="btn btn-primary btn-sm">Add Supplier</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th width="30">#</th>
								<th>Supplier Name</th>
								<th>Product</th>
								<th>Address</th>
								<th>Email</th>
								<th>Telephone</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 0;
							$statement = $pdo->prepare("SELECT * FROM tbl_supplier");
							if ($statement->execute()) {
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result as $row) {
									$i++;
									?>
									<tr>
										<td><?php echo $i; ?></td>
										<td><?php echo isset($row['supplier_name']) ? $row['supplier_name'] : 'N/A'; ?></td>
										<td><?php echo isset($row['product']) ? $row['product'] : 'N/A'; ?></td>
										<td><?php echo isset($row['address']) ? $row['address'] : 'N/A'; ?></td>
										<td><?php echo isset($row['email']) ? $row['email'] : 'N/A'; ?></td>
										<td><?php echo isset($row['telephone']) ? $row['telephone'] : 'N/A'; ?></td>
									</tr>
									<?php
								}
							} else {
								// Display an error message if the query execution fails
								echo "Error: " . $statement->errorInfo()[2];
							}
							?>							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<?php require_once('footer.php'); ?>
