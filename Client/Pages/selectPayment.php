<?php
require_once __DIR__ . '/../PHP/dbConfig.php';

$bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
$clientId = isset($_GET['client_id']) ? (int)$_GET['client_id'] : 0;

$bookingInfo = null;
if ($bookingId > 0) {
  $stmt = $conn->prepare(
    "SELECT b.booking_id, b.service_date, b.service_time, c.name AS chapel_name, c.price AS service_price
     FROM bookings b
     LEFT JOIN chapel_services c ON b.chapel_id = c.id
     WHERE b.booking_id = ?"
  );
  $stmt->bind_param("i", $bookingId);
  $stmt->execute();
  $resultInfo = $stmt->get_result();
  if ($resultInfo && $resultInfo->num_rows > 0) {
    $bookingInfo = $resultInfo->fetch_assoc();
  }
  $stmt->close();
}

$methods = [];
$sql = "SELECT id, bank_name, card_number, qr_image, status FROM payment_methods WHERE status = 'enabled' ORDER BY id DESC";
$result = $conn->query($sql);
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $methods[] = $row;
  }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Select Payment Method - DULCE</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="../CSS/style.css" />
    <style>
      .payment-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
      }
      @media (max-width: 768px) {
        .payment-grid {
          grid-template-columns: 1fr;
        }
      }
      .payment-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 16px;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
        position: relative;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
      }
      .payment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.12);
      }
      .payment-card h5 {
        margin-bottom: 6px;
      }
      .qr-box {
        width: 100%;
        max-width: 180px;
        height: 180px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        object-fit: cover;
        background: #f8fafc;
      }
      .select-btn {
        background: #2563eb;
        color: #fff;
        border: none;
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 600;
      }
      .card-number-display {
        font-size: 1.25rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        color: #0f172a;
        cursor: pointer;
        user-select: all;
      }
      .copy-hint {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 4px;
      }
    </style>
  </head>
  <body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
      <div class="container">
        <a class="navbar-brand" href="Index.html">
          <i class="bi bi-heart-pulse-fill"></i> DULCE
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item">
              <a class="nav-link" href="Index.html">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="chapel-services.html">Home Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="announcements.html">Announcements</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="obituaries.html">Obituaries</a>
            </li>
            <li class="nav-item">
              <a
                class="btn btn-register ms-lg-3"
                href="../../SignUp&Login/Pages/register.html"
                >Register</a
              >
            </li>
            <li class="nav-item">
              <a
                class="btn btn-login ms-lg-2"
                href="../../SignUp&Login/Pages/login.html"
                >Login</a
              >
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <section class="page-header">
      <div class="container text-center">
        <h1 class="page-title">Select Payment Method</h1>
        <p class="page-subtitle">
          Choose a payment method to continue your home service booking.
        </p>
      </div>
    </section>

    <section class="py-4">
      <div class="container">
        <?php if ($bookingInfo) : ?>
          <div class="card mb-4">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
              <div>
                <h5 class="mb-1">
                  Home Service: <?php echo htmlspecialchars($bookingInfo['chapel_name'] ?? ''); ?>
                </h5>
                <small class="text-muted">
                  Booking #<?php echo (int)$bookingInfo['booking_id']; ?>
                  <?php if (!empty($bookingInfo['service_date'])) : ?>
                    • <?php echo htmlspecialchars($bookingInfo['service_date']); ?>
                  <?php endif; ?>
                  <?php if (!empty($bookingInfo['service_time'])) : ?>
                    • <?php echo htmlspecialchars($bookingInfo['service_time']); ?>
                  <?php endif; ?>
                </small>
              </div>
              <div class="text-end">
                <div class="text-muted">Amount</div>
                <div class="h5 mb-0">
                  <?php echo $bookingInfo['service_price'] !== null
                    ? '₱' . number_format((float)$bookingInfo['service_price'], 2)
                    : '₱0.00'; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <?php if (empty($methods)) : ?>
          <div class="alert alert-warning text-center">
            No active payment methods available.
          </div>
        <?php else : ?>
          <div class="payment-grid">
            <?php foreach ($methods as $m) : ?>
              <div
                class="payment-card"
                data-id="<?php echo (int)$m['id']; ?>"
                data-bank="<?php echo htmlspecialchars($m['bank_name']); ?>"
                data-card="<?php echo htmlspecialchars($m['card_number']); ?>"
                data-qr="<?php echo htmlspecialchars($m['qr_image']); ?>"
              >
                <h5><?php echo htmlspecialchars($m['bank_name']); ?></h5>
                <div class="text-muted mb-2">
                  Card: **** **** **** <?php echo substr(preg_replace('/\D/', '', $m['card_number']), -4); ?>
                </div>
                <?php if (!empty($m['qr_image'])) : ?>
                  <img class="qr-box" src="../../<?php echo htmlspecialchars($m['qr_image']); ?>" alt="QR">
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <div
      class="modal fade"
      id="paymentSelectModal"
      tabindex="-1"
      aria-labelledby="paymentSelectModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="paymentSelectModalLabel">Payment Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3 align-items-start">
              <div class="col-md-7">
                <h5 id="modalBankName"></h5>
                <div class="card-number-display" id="modalCardNumber" title="Click to copy"></div>
                <div class="copy-hint">Click to copy card number</div>
                <form id="paymentSelectForm" method="POST" action="../PHP/saveBookingPayment.php" enctype="multipart/form-data">
                  <input type="hidden" name="booking_id" value="<?php echo $bookingId; ?>">
                  <input type="hidden" name="client_id" value="<?php echo $clientId; ?>">
                  <input type="hidden" name="payment_method_id" id="modalPaymentMethodId">
                  <div class="mb-3">
                    <label class="form-label">Upload Receipt</label>
                    <input class="form-control" type="file" name="receipt_image" accept="image/*" required>
                  </div>
                  <button class="select-btn" type="submit" <?php echo ($bookingId && $clientId) ? '' : 'disabled'; ?>>
                    Submit Payment
                  </button>
                  <?php if (!$bookingId || !$clientId) : ?>
                    <div class="text-muted mt-2">Missing booking/client info.</div>
                  <?php endif; ?>
                </form>
              </div>
              <div class="col-md-5 text-center">
                <img id="modalQrImage" class="qr-box" src="" alt="QR Code" style="display:none;">
                <div class="mt-2">
                  <a id="qrDownloadLink" class="btn btn-outline-secondary btn-sm" href="#" download style="display:none;">
                    Download QR
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JavaScript/session.js"></script>
    <script>
      const cards = document.querySelectorAll(".payment-card");
      const modalEl = document.getElementById("paymentSelectModal");
      const modal = new bootstrap.Modal(modalEl);
      const modalBankName = document.getElementById("modalBankName");
      const modalCardNumber = document.getElementById("modalCardNumber");
      const modalQrImage = document.getElementById("modalQrImage");
      const modalPaymentMethodId = document.getElementById("modalPaymentMethodId");
      const qrDownloadLink = document.getElementById("qrDownloadLink");

      cards.forEach((card) => {
        card.addEventListener("click", () => {
          const bank = card.getAttribute("data-bank") || "";
          const cardNumber = card.getAttribute("data-card") || "";
          const qr = card.getAttribute("data-qr") || "";
          const id = card.getAttribute("data-id");

          modalBankName.textContent = bank;
          modalCardNumber.textContent = cardNumber;
          modalPaymentMethodId.value = id;

          if (qr) {
            modalQrImage.src = `../../${qr}`;
            modalQrImage.style.display = "inline-block";
            qrDownloadLink.href = `../../${qr}`;
            qrDownloadLink.style.display = "inline-block";
          } else {
            modalQrImage.style.display = "none";
            qrDownloadLink.style.display = "none";
          }

          modal.show();
        });
      });

      modalCardNumber.addEventListener("click", async () => {
        const text = modalCardNumber.textContent.trim();
        if (!text) return;
        try {
          await navigator.clipboard.writeText(text);
        } catch (e) {
          const temp = document.createElement("textarea");
          temp.value = text;
          document.body.appendChild(temp);
          temp.select();
          document.execCommand("copy");
          document.body.removeChild(temp);
        }
      });
    </script>
  </body>
</html>
