<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
  <div class="h-full p-5">

    <!-- School ID -->
    <label class="input input-bordered flex items-center gap-2 mt-3">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70"
        id="id-icon">
        <path
          d="M1 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3z" />
        <path
          d="M4 6h8v2H4V6zm0 4h5v2H4v-2z" />
      </svg>
      <input type="text" class="grow" placeholder="School ID" minlength="7" maxlength="7" name="school_id"
        autocomplete="off" value="<?php echo isset($schoolId); ?>" />
    </label>

    <!-- First Name -->
    <label class="input input-bordered flex items-center gap-2 mt-3">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70"
        id="notebook-icon">
        <path
          d="M3 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H3zM4 2h8a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z" />
        <path
          d="M6 3v10h4V3H6z" />
      </svg>
      <input type="text" class="grow" placeholder="First Name" name="fname" autocomplete="off" />
    </label>

    <!-- Last Name -->
    <label class="input input-bordered flex items-center gap-2 mt-3">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70"
        id="notebook-icon">
        <path
          d="M3 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H3zM4 2h8a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z" />
        <path
          d="M6 3v10h4V3H6z" />
      </svg>
      <input type="text" class="grow" placeholder="Last Name" name="lname" autocomplete="off" />
    </label>

    <!-- Email -->
    <label class="input input-bordered flex items-center gap-2 mt-3">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70">
        <path
          d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
        <path
          d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
      </svg>
      <input type="text" class="grow" placeholder="Email" name="email" autocomplete="off" />
    </label>

    <!-- Year -->
    <label class="input input-bordered flex items-center gap-2 mt-3">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70">
        <path
          d="M3 0a1 1 0 0 1 1 1v1h8V1a1 1 0 0 1 2 0v1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h1V1a1 1 0 0 1 1-1zm0 3v10h10V3H3z" />
      </svg>
      <input type="text" class="grow" placeholder="Year" name="year" />
    </label>

    <!-- Department -->
    <label class="input input-bordered flex items-center gap-2 mt-3">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70">
        <path
          d="M3 0a1 1 0 0 1 1 1v1h8V1a1 1 0 0 1 2 0v1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h1V1a1 1 0 0 1 1-1zm0 3v10h10V3H3z" />
        <path
          d="M5 5h6v2H5V5zm0 3h6v2H5V8zm0 3h6v2H5v-2z" />
      </svg>
      <select
        name="department_id"
        required
        class="px-3 py-2 w-full border-s-4 shadow border-blue-900 text-black rounded-sm">
        <option value="" disabled selected class="text-white">Select Department</option>
        <?php
        $department = $conn->query("SELECT * FROM tbldepartment");
        while ($row = $department->fetch_assoc()): ?>
          <option value="<?php echo $row['department_id']; ?>"><?php echo htmlspecialchars($row['department_name']); ?></option>
        <?php endwhile; ?>
      </select>
    </label>

    <!-- Section -->
    <label class="input input-bordered flex items-center gap-2 mt-3">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70">
        <path
          d="M2 0a1 1 0 0 1 1 1v1h10V1a1 1 0 0 1 2 0v1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h1V1a1 1 0 0 1 1-1zm0 3v10h12V3H2z" />
        <path
          d="M4 5h8v2H4V5zm0 3h8v2H4V8zm0 3h8v2H4v-2z" />
      </svg>
      <select name="section_id" id="section" class="w-full text-black px-3 py-2 
                      border-s-4 border-blue-900 rounded-sm" required>
        <option value="" disabled selected>Select Section</option>
        <?php
        $section = $conn->query("SELECT * FROM tblsection");
        while ($row = $section->fetch_assoc()): ?>
          <option value="<?php echo $row['section_id']; ?>"><?php echo htmlspecialchars($row['section_name']); ?></option>
        <?php endwhile; ?>
      </select>
    </label>

    <div>
      <input type="submit" name="" id="" class="btn btn-md btn-outline w-full mt-2">
    </div>
  </div>
</body>

</html>