<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $title ?>
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
        }

        p {
            margin: 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .no-print {
            display: none;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                -webkit-print-color-adjust: exact;
            }
        }

        .meta-info {
            font-size: 10px;
            color: #999;
            margin-top: 5px;
        }
    </style>
    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</head>

<body>
    <div class="header">
        <h1>Response Form:
            <?= esc($form['title']) ?>
        </h1>
        <p>Total Responses:
            <?= count($responses) ?>
        </p>
        <p class="meta-info">Dicetak pada:
            <?= date('d/m/Y H:i:s') ?>
        </p>
    </div>

    <?php
    $db = \Config\Database::connect();
    $formFields = $db->table('form_fields')
        ->where('form_id', $form['id'])
        ->where('is_active', true)
        ->orderBy('order', 'ASC')
        ->get()
        ->getResultArray();
    ?>

    <?php if (!empty($responses) && !empty($formFields)): ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 100px;">Waktu</th>
                    <th>Responden</th>
                    <?php foreach ($formFields as $field): ?>
                        <th>
                            <?= esc($field['label']) ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($responses as $response):
                    // Organize field responses for this row
                    $fieldResponses = $db->table('form_field_responses')
                        ->where('response_id', $response['id'])
                        ->get()
                        ->getResultArray();

                    $fieldResponsesMap = [];
                    foreach ($fieldResponses as $fr) {
                        $fieldResponsesMap[$fr['field_id']] = $fr;
                    }
                    ?>
                    <tr>
                        <td style="text-align: center;">
                            <?= $no++ ?>
                        </td>
                        <td>
                            <?= date('d/m/Y H:i', strtotime($response['submitted_at'])) ?>
                        </td>
                        <td>
                            <strong>
                                <?= esc($response['respondent_name'] ?? '-') ?>
                            </strong><br>
                            <span class="meta-info">
                                <?= esc($response['respondent_email'] ?? '-') ?>
                            </span>
                        </td>
                        <?php foreach ($formFields as $field):
                            $fieldResponse = $fieldResponsesMap[$field['id']] ?? null;
                            ?>
                            <td>
                                <?php if ($fieldResponse): ?>
                                    <?php if ($field['field_type'] == 'file' && $fieldResponse['file_path']): ?>
                                        [File:
                                        <?= esc(basename($fieldResponse['file_path'])) ?>]
                                    <?php else: ?>
                                        <?php
                                        $value = $fieldResponse['value'];
                                        $decoded = json_decode($value, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                            echo esc(implode(', ', $decoded));
                                        } else {
                                            echo nl2br(esc($value));
                                        }
                                        ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada data response.</p>
    <?php endif; ?>
</body>

</html>