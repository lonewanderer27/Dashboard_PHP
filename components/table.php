<?php
global $cn, $user, $ADMIN, $ROLE;
$sql = "SELECT * FROM employees";
$admin = false;
// execute query
$rs = $cn->query($sql);

// check if the user's role is admin
if ($user["role"] == $ADMIN) {
    $admin = true;
}
?>

<div class="container mt-3 mb-5">
    <?php if ($rs->num_rows > 0): ?>
        <table id="employeeList" class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>EmpID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Job Title</th>
                <th>Email</th>
                <th>Phone</th>
                <?php if ($admin): ?>
                    <th>Action</th>
                <?php endif ?>
            </tr>
            </thead>
            <?php while ($row = $rs->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['EmployeeID'] ?></td>
                    <td><?= $row['EmployeeFN'] ?></td>
                    <td><?= $row['EmployeeLN'] ?></td>
                    <td><?= $row['JobTitle'] ?></td>
                    <td><?= $row['EmployeeEmail'] ?></td>
                    <td><?= $row['EmployeePhone'] ?></td>
                    <?php if ($admin): ?>
                        <td class="d-flex gap-2">
                            <a class="btn btn-warning" href="?token=<?php echo $row['EmployeeID'] ?>">Edit</a>
                            <a class="btn btn-danger"
                               href="../delete.php?token=<?php echo $row['EmployeeID'] ?>">Delete</a>
                        </td>
                    <?php endif ?>
                </tr>
            <?php endwhile ?>
        </table>
    <?php else: ?>
        <h1>No Employees Found</h1>
    <?php endif ?>
</div>
