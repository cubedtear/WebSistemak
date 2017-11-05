<?xml version="1.0"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:csl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/">
        <html>
            <body>
                <h2>Quizzes</h2>
                <table border="1">
                    <tr>
                        <th>Galdera</th>
                        <th>Erantzun zuzena</th>
                        <th>Erantzun okerrak</th>
                        <th>Gaia</th>
                        <th>Zailtasuna</th>
                    </tr>
                    <xsl:for-each select="assessmentItems/assessmentItem">
                        <tr>
                            <td><xsl:value-of select="itemBody"/></td>
                            <td><xsl:value-of select="correctResponse/value"/></td>
                            <td><ul>
                                <xsl:for-each select="incorrectResponses/value">
                                        <li><xsl:value-of select="self::node()" /></li>
                                </xsl:for-each>
                            </ul></td>
                            <td><xsl:value-of select="@subject" /></td>
                            <td><xsl:value-of select="@complexity" /></td>
                        </tr>
                    </xsl:for-each>
                </table>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>