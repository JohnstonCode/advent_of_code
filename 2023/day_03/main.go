package main

import (
	"fmt"
	"image"
	"os"
	"regexp"
	"strconv"
	"strings"
)

type partNum struct {
	num   int
	start image.Point
	end   image.Point
}

type symbol struct {
	char string
	pos  image.Point
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	part1 := part1(string(content))
	part2 := part2(string(content))

	fmt.Printf("Part1: %s\n", part1)
	fmt.Printf("Part2: %s\n", part2)
}

func part1(input string) string {
	lines := strings.Split(input, "\n")
	numRegexPattern := `\d+`
	symRegexPattern := `[^0-9|\.|\n]`
	numRegex := regexp.MustCompile(numRegexPattern)
	symRegex := regexp.MustCompile(symRegexPattern)
	var partNums []partNum
	var symbols []symbol
	var numbers []int

	for y, line := range lines {
		matches := numRegex.FindAllString(line, -1)
		matchesPos := numRegex.FindAllStringIndex(line, -1)

		if len(matches) > 0 {
			for i := 0; i < len(matches); i++ {
				num, _ := strconv.Atoi(matches[i])
				cols := matchesPos[i]

				partNums = append(partNums, partNum{
					num:   num,
					start: image.Point{Y: y, X: cols[0]},
					end:   image.Point{Y: y, X: cols[0] + len(matches[i]) - 1},
				})
			}
		}

		matches = symRegex.FindAllString(line, -1)
		matchesPos = symRegex.FindAllStringIndex(line, -1)

		if len(matches) > 0 {
			for i := 0; i < len(matches); i++ {
				sym := matches[i]

				symbols = append(symbols, symbol{
					char: sym,
					pos:  image.Point{Y: y, X: matchesPos[i][0]},
				})
			}
		}
	}

	for _, partNum := range partNums {
		for _, symbol := range symbols {
			neighbours := []image.Point{
				{Y: symbol.pos.Y - 1, X: symbol.pos.X},
				{Y: symbol.pos.Y - 1, X: symbol.pos.X + 1},
				{Y: symbol.pos.Y, X: symbol.pos.X + 1},
				{Y: symbol.pos.Y + 1, X: symbol.pos.X + 1},
				{Y: symbol.pos.Y + 1, X: symbol.pos.X},
				{Y: symbol.pos.Y + 1, X: symbol.pos.X - 1},
				{Y: symbol.pos.Y, X: symbol.pos.X - 1},
				{Y: symbol.pos.Y - 1, X: symbol.pos.X - 1},
			}

			for _, neighbour := range neighbours {
				if neighbour.Y == partNum.start.Y && neighbour.X >= partNum.start.X && neighbour.X <= partNum.end.X {
					numbers = append(numbers, partNum.num)

					break
				}
			}
		}
	}

	sum := 0

	for _, num := range numbers {
		sum += num
	}

	return strconv.Itoa(sum)
}

func part2(input string) string {
	lines := strings.Split(input, "\n")
	numRegexPattern := `\d+`
	symRegexPattern := `[^0-9|\.|\n]`
	numRegex := regexp.MustCompile(numRegexPattern)
	symRegex := regexp.MustCompile(symRegexPattern)
	var partNums []partNum
	var symbols []symbol

	for y, line := range lines {
		matches := numRegex.FindAllString(line, -1)
		matchesPos := numRegex.FindAllStringIndex(line, -1)

		if len(matches) > 0 {
			for i := 0; i < len(matches); i++ {
				num, _ := strconv.Atoi(matches[i])
				cols := matchesPos[i]

				partNums = append(partNums, partNum{
					num:   num,
					start: image.Point{Y: y, X: cols[0]},
					end:   image.Point{Y: y, X: cols[0] + len(matches[i]) - 1},
				})
			}
		}

		matches = symRegex.FindAllString(line, -1)
		matchesPos = symRegex.FindAllStringIndex(line, -1)

		if len(matches) > 0 {
			for i := 0; i < len(matches); i++ {
				sym := matches[i]

				symbols = append(symbols, symbol{
					char: sym,
					pos:  image.Point{Y: y, X: matchesPos[i][0]},
				})
			}
		}
	}

	sum := 0

	for _, symbol := range symbols {
		if symbol.char != "*" {
			continue
		}

		neighbours := []image.Point{
			{Y: symbol.pos.Y - 1, X: symbol.pos.X},
			{Y: symbol.pos.Y - 1, X: symbol.pos.X + 1},
			{Y: symbol.pos.Y, X: symbol.pos.X + 1},
			{Y: symbol.pos.Y + 1, X: symbol.pos.X + 1},
			{Y: symbol.pos.Y + 1, X: symbol.pos.X},
			{Y: symbol.pos.Y + 1, X: symbol.pos.X - 1},
			{Y: symbol.pos.Y, X: symbol.pos.X - 1},
			{Y: symbol.pos.Y - 1, X: symbol.pos.X - 1},
		}

		var ratios []int

		for _, partNum := range partNums {
			for _, neighbour := range neighbours {
				if neighbour.Y == partNum.start.Y && neighbour.X >= partNum.start.X && neighbour.X <= partNum.end.X {
					ratios = append(ratios, partNum.num)

					break
				}
			}
		}

		if len(ratios) == 2 {
			sum += ratios[0] * ratios[1]
		}
	}

	return strconv.Itoa(sum)
}
