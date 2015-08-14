
    <div class="pagination">
      <table border="0">
        <tr>
          <td><?php if ($pageNum_propRS > 0) { // Show if not first page ?>
              <a class="button" href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, 0, $queryString_propRS); ?>">&nbsp;First&nbsp;</a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_propRS > 0) { // Show if not first page ?>
              <a class="button"href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, max(0, $pageNum_propRS - 1), $queryString_propRS); ?>">&nbsp;Previous&nbsp;</a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_propRS < $totalPages_propRS) { // Show if not last page ?>
              <a class="button" href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, min($totalPages_propRS, $pageNum_propRS + 1), $queryString_propRS); ?>">&nbsp;Next&nbsp;</a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_propRS < $totalPages_propRS) { // Show if not last page ?>
              <a class="button" href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, $totalPages_propRS, $queryString_propRS); ?>">&nbsp;Last&nbsp;</a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table>
    </div>
               <div style="clear:both;"></div>

