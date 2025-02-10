<?php
// Define the number of criteria per page
$limit = 5;

// Get the current page from URL, default is 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max($page, 1); // Ensure page is at least 1

// Calculate offset
$offset = ($page - 1) * $limit;

// Get the total number of criteria
$totalCriteria = count($criteriaList);
$totalPages = ceil($totalCriteria / $limit);

// Slice the array to get only the current page items
$paginatedCriteria = array_slice($criteriaList, $offset, $limit);
?>

<!-- Criteria List -->
<div id="criterialist" class="mt-6">
  <?php if (count($paginatedCriteria) > 0): ?>
    <ol class="space-y-4">
      <?php foreach ($paginatedCriteria as $listCriteria): ?>
        <li>
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg shadow-sm">
            <span class="font-semibold text-gray-800">
              <?php echo htmlspecialchars($listCriteria['criteria']); ?>
              <input type="hidden" name="criteria_id[<?php echo $listCriteria['criteria_id']; ?>]" value="<?php echo $listCriteria['criteria_id']; ?>">
            </span>

            <div class="flex space-x-3">
              <?php for ($i = 1; $i <= 4; $i++): ?>
                <label class="inline-flex items-center">
                  <input type="radio" name="rating[<?php echo $listCriteria['criteria_id']; ?>]" value="<?php echo $i; ?>" required class="text-blue-600 focus:ring-blue-500">
                  <span class="ml-1 text-gray-700"><?php echo $i; ?></span>
                </label>
              <?php endfor; ?>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
  <?php else: ?>
    <div class="text-gray-500">No Criteria Available</div>
  <?php endif; ?>
</div>

<!-- Pagination Controls -->
<div class="mt-4 flex justify-center space-x-4">
  <?php if ($page > 1): ?>
    <a href="?page=<?php echo ($page - 1); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md shadow">Previous</a>
  <?php endif; ?>

  <?php if ($page < $totalPages): ?>
    <a href="?page=<?php echo ($page + 1); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md shadow">Next</a>
  <?php endif; ?>
</div>