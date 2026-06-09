<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <title>عقد إلكتروني لتقديم خدمات مخبرية</title>

  <style>
    body {
      margin: 0;
      background: #f3f3f3;
      font-family: "Amiri", "Times New Roman", serif;
      color: #000;
    }

    .pdf-page {
      width: 794px;
      min-height: 1123px;
      margin: 30px auto;
      background: white;
      padding: 85px 75px;
      box-sizing: border-box;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
      direction: rtl;
      line-height: 2.1;
      font-size: 16px;
    }

    h1 {
      text-align: center;
      font-size: 20px;
      margin: 0 0 60px;
      font-weight: bold;
    }

    p {
      margin: 0 0 18px;
      text-align: justify;
    }

    .intro {
      font-weight: bold;
      margin-bottom: 25px;
    }

    .field {
      font-weight: bold;
      border-bottom: 1px dotted #000;
      padding: 0 8px;
      min-width: 140px;
      display: inline-block;
      text-align: center;
    }

    .section-title {
      font-weight: bold;
      text-decoration: underline;
      text-align: center;
      margin: 35px 0 20px;
      font-size: 18px;
    }

    .clause {
      margin-bottom: 14px;
    }

    .sign-title {
      text-align: center;
      font-weight: bold;
      margin-top: 55px;
    }

    .signature-text {
      text-align: center;
      margin-top: 5px;
    }

    .signature-box {
      width: 280px;
      height: 120px;
      margin: 35px auto 0;
      border: 1px dashed #999;
      border-bottom: 1px dotted #000;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #777;
      font-size: 14px;
    }

    .signature-box img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    @media print {
      body {
        background: white;
      }

      .pdf-page {
        margin: 0;
        box-shadow: none;
        width: 100%;
        min-height: 100vh;
      }
    }
  </style>
</head>

<body>
  <main class="pdf-page">
    <h1>عقد إلكتروني لتقديم خدمات مخبرية</h1>

    <p class="intro">تم الاتفاق بين كلا من:</p>

    <p>
      <strong>الطرف الأول (المخبر):</strong>
      <span class="field">{{ $labName ?? 'اسم المخبر' }}</span>
      /
      <span class="field">{{ $labNumber ?? 'رقم المخبر' }}</span>
    </p>

    <p>
      <strong>الطرف الثاني (المستهلك الإلكتروني):</strong>
      <span class="field">{{ $studentName ?? 'اسم الطالب' }}</span>
      /
      <span class="field">{{ $studentPhone ?? 'رقم الهاتف' }}</span>
    </p>

    <div class="section-title">تمهيد:</div>

    <p>
      حيث أن الطرف الثاني يرغب في التعامل مع الطرف الأول للاستفادة من الخدمات المخبرية
      أو استغلال الأجهزة المتاحة عبر تطبيق <strong>LabLink</strong>، فقد اتفق الطرفان
      على مراجعة هذا العقد والاتفاق عليه حسب البنود التالية:
    </p>

    <div class="section-title">الخدمات والأجهزة المطلوبة:</div>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; direction: rtl; font-size: 14px;">
      <thead>
        <tr style="background-color: #f2f2f2; border-bottom: 2px solid #ccc;">
          <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">الخدمة / الجهاز</th>
          <th style="padding: 8px; text-align: center; border: 1px solid #ddd;">الكمية</th>
          <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">السعر الفرعي</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orderItems as $item)
        <tr style="border-bottom: 1px solid #ddd;">
          <td style="padding: 8px; text-align: right; border: 1px solid #ddd;">{{ $item->product->name_ar ?? $item->product->name ?? 'خدمة' }}</td>
          <td style="padding: 8px; text-align: center; border: 1px solid #ddd;">{{ $item->quantity }}</td>
          <td style="padding: 8px; text-align: left; border: 1px solid #ddd;">{{ $item->price ? ($item->price * $item->quantity) . ' DA' : '-' }}</td>
        </tr>
        @endforeach
      </tbody>
      @if(!empty($totalPrice))
      <tfoot>
        <tr style="font-weight: bold; background-color: #f9f9f9;">
          <td colspan="2" style="padding: 8px; text-align: right; border: 1px solid #ddd;">الإجمالي:</td>
          <td style="padding: 8px; text-align: left; border: 1px solid #ddd;">{{ $totalPrice }} DA</td>
        </tr>
      </tfoot>
      @endif
    </table>

    <p class="clause">
      <strong>– البند الأول:</strong>
      يعتبر التمهيد أعلاه ومعطيات الحجز «نوع الخدمة/الجهاز» جزءاً لا يتجزأ من هذا العقد ومفسراً له.
    </p>

    <p class="clause">
      <strong>– البند الثاني:</strong>
      السعر الإجمالي الموضح في واجهة التطبيق هو القيمة المالية النهائية والملزمة المتفق عليها
      بين الطرفين لقاء الخدمة المطلوبة، ولا يعتد ولا يعمل بأي تسعيرات أو مقترحات سابقة جرت
      المفاوضة عليها عبر التطبيق قبل صدور هذا العقد وتوقيعه.
    </p>

    <p class="clause">
      <strong>– البند الثالث:</strong>
      يلتزم المستهلك التزاماً صارماً بقواعد السلامة الداخلية للمخبر والتدابير البيولوجية
      فور دخوله لإجراء تجاربه.
    </p>

    <p class="clause">
      <strong>– البند الرابع:</strong>
      أي مخالفة للشروط أو إخلال بقواعد الانضباط تبطل هذا العقد تلقائياً وبقوة القانون،
      ويحق للمخبر إلغاء الحجز فوراً ودون أدنى مسؤولية.
    </p>

    <p class="clause">
      <strong>– البند الخامس:</strong>
      يتم دفع المبلغ المتفق عليه كاملاً فور الانتهاء تماماً من إجراء التجارب بنجاح داخل المخبر
      والاستفادة من الخدمة.
    </p>

    <p class="clause">
      <strong>– البند السادس:</strong>
      يلتزم الطرفان بالسرية المطلقة لجميع البيانات، الأبحاث، النتائج العلمية المستخرجة أو المتبادلة
      أثناء تنفيذ العقد، ويحظر تماماً إفشاؤها أو استغلالها دون إذن كتابي صريح.
    </p>

    <p class="sign-title">الإقرار والتوقيع الإلكتروني:</p>

    <p class="signature-text">
      بالضغط على زر القبول الأخضر أدناه والتوقيع باللمس،<br />
      "أقر بأني اطلعت وفهمت كافة البنود المذكورة أعلاه وأوافق عليها"
    </p>

    <div class="signature-box" style="height: 120px; text-align: center;">
      @if (!empty($signaturePaths))
      <svg width="100%" height="100%" viewBox="0 0 350 200" style="width: 280px; height: 120px; display: block; margin: 0 auto;">
        @foreach($signaturePaths as $path)
          <path d="{!! $path !!}" fill="none" stroke="#1d4ed8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
        @endforeach
      </svg>
      @elseif (!empty($signature))
      <img src="{{ $signature }}" alt="signature" />
      @else
      مكان التوقيع
      @endif
    </div>
  </main>
</body>

</html>