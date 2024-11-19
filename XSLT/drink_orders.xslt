<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <!-- Parameetrid, mis tulevad väljastpoolt ja mida kasutatakse andmete filtreerimiseks -->
    <xsl:param name="searchField" select="''"/>
    <xsl:param name="searchValue" select="''"/>

    <xsl:output method="html" indent="yes" encoding="utf-8"/>

    <xsl:template match="/">
        <html>
            <body>
                <h1>Drink orders view</h1>
                <!-- Otsinguvorm, mille kaudu saab päringuid teha -->
                <form id="searchForm" method="GET" action="Default.aspx" onsubmit="event.preventDefault(); sendSearchRequest();">
                    <label for="searchField">Search by:</label>
                    <!-- Valikuväli otsitava välja määramiseks -->
                    <select name="searchField" id="searchField">
                        <option value="name">Drink Name</option>
                        <option value="price">Drink Price</option>
                        <option value="amount">Drink Amount</option>
                        <option value="sugar_level">Sugar Level</option>
                        <option value="cup_type">Cup Type</option>
                        <option value="method">Payment Method</option>
                        <option value="paid">Payment Paid</option>
                        <option value="change">Payment Change</option>
                        <option value="status">Status</option>
                        <option value="datetime">Datetime</option>
                    </select>
                    <br></br>
                    <label for="searchValue">Value:</label>
                    <!-- Sisendväli otsitava väärtuse jaoks -->
                    <input type="text" name="searchValue" id="searchValue" />

                    <button type="button" onclick="sendSearchRequest()">Search</button>
                    <button type="button" onclick="sendAllDataRequest()">All</button>
                </form>
                <!-- Veergude valiku lüliti -->				<input type="checkbox" id="toggleColumnCheckbox" onchange="toggleColumnSelection()"> </input>
                Select Columns

                <div id="columnSelection">
                    <!-- Veergude kuvamise valikud -->
                    <label>
                        <input type="checkbox" class="column-checkbox" data-column="0" checked="" onchange="applyColumnSelection()"> Datetime </input>
                    </label>
                    <br>	</br>
                    <label>
                        <input type="checkbox" class="column-checkbox" data-column="1" checked="" onchange="applyColumnSelection()"> Drink Name </input>
                    </label>
                    <br>	</br>
                    <label>
                        <input type="checkbox" class="column-checkbox" data-column="2" checked="" onchange="applyColumnSelection()"> Drink Price</input>
                    </label>
                    <br>	</br>
                    <label>
                        <input type="checkbox" class="column-checkbox" data-column="3" checked="" onchange="applyColumnSelection()"> Drink Amount</input>
                    </label>
                    <br>	</br>
                    <label>
                        <input type="checkbox" class="column-checkbox" data-column="4" checked="" onchange="applyColumnSelection()"> Drink Sugar Level</input>
                    </label>
                    <br>	</br>
                    <label>
                        <input type="checkbox" class="column-checkbox" data-column="5" checked="" onchange="applyColumnSelection()"> Drink Cup Type</input>
                    </label>
                    <br>	</br>
                    <label>
                        <input type="checkbox" class="column-checkbox" data-column="6" checked="" onchange="applyColumnSelection()"> Payment Method</input>
                    </label>
                    <br>	</br>
                    <label>
                        <input type="checkbox" class="column-checkbox" data-column="7" checked="" onchange="applyColumnSelection()"> Payment Paid</input>
                    </label>
                    <br>	</br>
                    <label>
                        <input type="checkbox" class="column-checkbox" data-column="8" checked="" onchange="applyColumnSelection()"> Payment Change</input>
                    </label>
                    <br>	</br>

                    <label>
                        <input type="checkbox" class="column-checkbox" data-column="9" checked="" onchange="applyColumnSelection()"> Status</input>
                    </label>
                    <br>	</br>


                </div>

                <!-- Tabel joogitellimuste kuvamiseks -->
                <table id="drinkTable">
                    <tr>
                        <th onclick="sortTable(0)">Datetime</th>
                        <th onclick="sortTable(1)">Drink Name</th>
                        <th onclick="sortTable(2)">Drink Price</th>
                        <th onclick="sortTable(3)">Drink Amount</th>
                        <th onclick="sortTable(4)">Drink Sugar Level</th>
                        <th onclick="sortTable(5)">Drink Cup Type</th>
                        <th onclick="sortTable(6)">Payment Method</th>
                        <th onclick="sortTable(7)">Payment Paid</th>
                        <th onclick="sortTable(8)">Payment Change</th>
                        <th onclick="sortTable(9)">Status</th>
                    </tr>
                    <!-- Tabeli read luuakse joogitellimuste põhjal -->
                    <xsl:for-each select="drink_orders/drink_order[
                        ($searchField = 'name' and contains(translate(drink/name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), translate($searchValue, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'))) or
                        ($searchField = 'price' and contains(translate(drink/price, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), translate($searchValue, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'))) or
                        ($searchField = 'amount' and contains(translate(drink/amount, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), translate($searchValue, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'))) or
                        ($searchField = 'sugar_level' and contains(translate(drink/sugar_level, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), translate($searchValue, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'))) or
                        ($searchField = 'cup_type' and contains(translate(drink/cup_type, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), translate($searchValue, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'))) or
                        ($searchField = 'method' and contains(translate(payment/method, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), translate($searchValue, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'))) or
                        ($searchField = 'paid' and contains(translate(payment/paid, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), translate($searchValue, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'))) or
                        ($searchField = 'change' and contains(translate(payment/change, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), translate($searchValue, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'))) or
                        ($searchField = 'status' and contains(translate(status, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), translate($searchValue, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'))) or
                        ($searchField = 'datetime' and contains(translate(datetime, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), translate($searchValue, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'))) or
						($searchField = '' and contains(translate(drink/name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), translate($searchValue, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')))

                    ]">
                        <!-- Tabeli andmed, mis vastavad filtrile -->
                        <tr>
                            <td>
                                <xsl:value-of select="datetime" />
                            </td>
                            <td>
                                <xsl:value-of select="drink/name" />
                            </td>
                            <td>
                                <xsl:value-of select="drink/price" />
                            </td>
                            <td>
                                <xsl:value-of select="drink/amount" />
                            </td>
                            <td>
                                <xsl:value-of select="drink/sugar_level" />
                            </td>
                            <td>
                                <xsl:value-of select="drink/cup_type" />
                            </td>
                            <td>
                                <xsl:value-of select="payment/method" />
                            </td>
                            <td>
                                <xsl:value-of select="payment/paid" />
                            </td>
                            <td>
                                <xsl:value-of select="payment/change" />
                            </td>
                            <td>
                                <xsl:value-of select="status" />
                            </td>
                        </tr>
                    </xsl:for-each>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>