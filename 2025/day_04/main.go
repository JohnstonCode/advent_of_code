package main

import (
	"fmt"
	"log"
	"os"
	"strings"
)

type point struct {
	x, y int
}

var directions = []point{
	{0, -1},
	{1, -1},
	{1, 0},
	{1, 1},
	{0, 1},
	{-1, 1},
	{-1, 0},
	{-1, -1},
}

func main() {
	input, err := os.ReadFile("input.txt")
	if err != nil {
		log.Fatal(err)
	}

	lines := strings.Split(strings.TrimSpace(string(input)), "\n")
	if len(lines) == 0 {
		log.Fatal("empty input")
	}

	height := len(lines)
	width := len(lines[0])

	grid := make([][]byte, height)
	for y, line := range lines {
		grid[y] = []byte(line)
	}

	fmt.Println(part1(grid, width, height))
	fmt.Println(part2(grid, width, height))
}

func part1(grid [][]byte, height, width int) int {
	rolls := 0

	for y := 0; y < height; y++ {
		for x := 0; x < width; x++ {
			if grid[y][x] != '@' {
				continue
			}

			adjacent := countAdjacent(grid, x, y)
			if adjacent < 4 {
				rolls++
			}
		}
	}

	return rolls
}

func part2(grid [][]byte, height, width int) int {
	totalRemoved := 0

	for {
		var toRemove []point

		for y := 0; y < height; y++ {
			for x := 0; x < width; x++ {
				if grid[y][x] != '@' {
					continue
				}

				adjacent := countAdjacent(grid, x, y)

				if adjacent < 4 {
					toRemove = append(toRemove, point{x, y})
				}
			}
		}

		if len(toRemove) == 0 {
			break
		}

		for _, p := range toRemove {
			grid[p.y][p.x] = '.'
		}

		totalRemoved += len(toRemove)
	}

	return totalRemoved
}

func countAdjacent(grid [][]byte, x, y int) int {
	height := len(grid)
	if height == 0 {
		return 0
	}
	width := len(grid[0])

	count := 0

	for _, d := range directions {
		nx := x + d.x
		ny := y + d.y

		if ny < 0 || ny >= height || nx < 0 || nx >= width {
			continue
		}

		if grid[ny][nx] == '@' {
			count++
		}
	}

	return count
}
