<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>شهادة بالمستوى الدراسي</title>
</head>
<body style="direction: rtl; font-family: DejaVu Sans; font-size: 30px; line-height: 1.9; color:#000; margin:12mm;">

  <!-- إطار خارجي -->
  <table style="width:100%; border:1.4px solid #000; border-spacing:0; border-collapse:separate;">
    <tr><td>
      <!-- إطار داخلي -->
      <table style="width:100%; border:1px solid #000; margin:4mm; border-spacing:0; border-collapse:separate;">
        <tr><td style="padding:12mm 11mm;">

          <!-- الترويسة -->
          <table style="width:100%; border-spacing:0; border-collapse:separate; font-size:20px; line-height:1.6;">
            <tr>
              <td style="text-align:center; vertical-align:top; width:33%;font-size:40px;">
                دولة ليبيا<br>حكومة الوحدة الوطنية<br>وزارة التربية والتعليم
              </td>

              <td style="text-align:center; vertical-align:top; width:34%;">
                <table style="margin:0 auto; border-spacing:0; border-collapse:separate; width:auto;">
                  <tr>
                    <td style="width:50mm; height:50mm; border:1px solid #000;"></td>
                  </tr>
                </table>

                <!-- التاريخ / الرقم: كل خانة عبارة عن جدول داخلي بخط سفلي -->
                <table style="margin:4mm auto 0; border-spacing:6mm 0; border-collapse:separate; width:auto;">
                  <tr>
                    <td>التاريخ:</td>
                    <td>
                      <table style="border-spacing:0; border-collapse:separate; width:30mm;">
                        <tr><td style="border-bottom:1px solid #000;">{{ $cert->issue_date ?? '' }}</td></tr>
                      </table>
                    </td>
                    <td>الرقم:</td>
                    <td>
                      <table style="border-spacing:0; border-collapse:separate; width:30mm;">
                        <tr><td style="border-bottom:1px solid #000;">{{ $cert->code }}</td></tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>

              <td style="text-align:center; vertical-align:top; width:33%;font-size:40px;">

                مراقبة تعليم القطرون<br>مدرسة القطرون الثانوية
              </td>
            </tr>
          </table>

          <!-- العنوان داخل جدول -->
          <table style="width:100%; border-spacing:0; border-collapse:separate; margin:8mm 0 6mm;">
            <tr>
              <td style="text-align:center;">
                <table style="margin:0 auto; border-spacing:0; border-collapse:separate; width:auto;">
                  <tr>
                    <td style="border:1px solid #000; padding:4mm 14mm; font-weight:bold; font-size:40px;">
                      شهادة بالمستوى الدراسي
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
<br>
<br>
<br>
          <!-- سطر: الطالب -->
          <table style="width:100%; border-spacing:0; border-collapse:separate; margin:3mm 0;font-size:30px;">
            <tr>
              <td>تشهد إدارة مدرسة القطرون الثانوية بأن الطالب /{{ $cert->student_name }}


              </td>
              <td style="width:mm;">

              </td>
            </tr>
          </table>

          <!-- سطر: الصف + القسم + الدور -->
          <table style="width:100%; border-spacing:0; border-collapse:separate; margin:3mm 0;font-size:30px;">
            <tr>
              <td>قد اجتاز امتحان الصف / {{ $cert->class_name }}  , القسم : {{ $cert->department }}  الدور :
                 {{ $cert->round_name }} وتحت رقم الجلوس  : {{ $cert->seat_no }}</td>
              <td style="width:34mm;">
                <table style="border-spacing:0; border-collapse:separate; width:34mm;">
                  <tr><td style="border-bottom:1px solid #000;"></td></tr>
                </table>
              </td>


            </tr>
          </table>





          <!-- العام + صفة القيد -->
          <table style="width:100%; border-spacing:0; border-collapse:separate; margin:3mm 0;font-size:30px;">
            <tr>
              <td>للعام الدراسي : {{ $cert->academic_year }}  صفة القيد : {{ $cert->grade_of_year }}</td>
              <td style="width:22mm;">

              </td>

            </tr>
          </table>

          <!-- النتيجة -->
          <table style="width:100%; border-spacing:0; border-collapse:separate; margin:3mm 0;font-size:30px;">
            <tr>
              <td>وكانت النتيجة  بتقدير عام : {{ $cert->general_remark }} ومجموع درجاته : {{ $cert->total_marks }}  ونسبة :{{ $cert->percentage }}</td>
              <td style="width:34mm;">
                <table style="border-spacing:0; border-collapse:separate; width:34mm;">
                  <tr><td style="border-bottom:1px solid #000;"></td></tr>
                </table>
              </td>


            </tr>
          </table>

<br>

<div style="margin:3mm 0; text-align:center; width:100%;">
  وبطلبٍ منه أُعطيت له هذه الشهادة لاستعمالها فيما يُخوِّله القانون.
</div>
          <div style="text-align:center; margin:8mm 0 6mm; font-weight:600;">
            والسلام عليكم ورحمة الله وبركاته
          </div>
<br>
          <!-- التواقيع -->
          <div style="margin-top:10mm;">
            <table style="width:100%; border-spacing:0; border-collapse:separate; margin:3mm 0; font-size:30px;">
              <tr>
                <td style="width:22mm;">أُعِدّه:................................................</td>
                <td style="width:56mm;">

                </td>
                <td style="width:22mm; text-align:center;">التوقيع:................................................</td>
                <td style="width:56mm;">

                </td>
              </tr>
              <tr>
                <td>راجعه:................................................</td>
                <td style="width:56mm;">
                  <table style="border-spacing:0; border-collapse:separate; width:56mm;">
                    <tr><td style="border-bottom:1px solid #000;">&#160;</td></tr>
                  </table>
                </td>
                <td style="text-align:center;">التوقيع:................................................</td>
                <td style="width:56mm;">

                </td>
              </tr>
              <tr>
                <td colspan="1">اعتماد مكتب الامتحانات /</td>
                <td colspan="3" style="width:90mm;">
                  <table style="border-spacing:0; border-collapse:separate; width:90mm;">
                    <tr><td style="border-bottom:1px solid #000;">&#160;</td></tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>سُجلت تحت رقم /</td>
                <td colspan="3" style="width:22mm;">
                  <table style="border-spacing:0; border-collapse:separate; width:22mm;">
                    <tr><td style="border-bottom:1px solid #000;">{{ $cert->code }}</td></tr>
                  </table>
                </td>
              </tr>
            </table>
          </div>

        </td></tr>
      </table>
    </td></tr>
  </table>

</body>
</html>
